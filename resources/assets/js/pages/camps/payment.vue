<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Almost There...</div>
    </top-space>

    <section class="mbr-section article content1 cid-qZDljz1dnu" id="content1-a5">
      <div class="mbr-text col-12 col-md-12 mbr-fonts-style display-7">
        <h2>TOTAL: ${{ total_fee }}</h2>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDmx10m9q" id="content8-a7">
      <div class="container">
        <form @submit.prevent="checkout">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <v-button :loading="checkout_busy" class="btn btn-secondary display-5 btn-radius">
                <span class="mbri-credit-card mbr-iconfont mbr-iconfont-btn"></span>
                Pay Now
              </v-button>
            </div>
          </div>
        </div>
        </form>
      </div>
    </section>

    <section class="mbr-section article content1 cid-qZDljz1dnu">
      <form @submit.prevent="apply">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-4 mbr-fonts-style display-7">
            <p>Gift Card/Voucher Code:</p>
            <input type="text" class="form-control" v-model="gift.code">
            <v-button :loading="form.busy" class="btn btn-primary btn-form display-4 btn-radius">
              Apply
            </v-button>
            <p><br></p>
            <p><br></p>
          </div>
        </div>
      </div>
      </form>
    </section>

    <section class="mbr-section article content1 cid-qZDmUrxXz0">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
            <h3>Camp Breakdown</h3>
            <table align="center">
              <tr>
                <th>Name</th>
                <th>Camp</th>
                <th>Price</th>
              </tr>
              <tr v-for="(enrol, index) in payable_enrols" :key="index">
                <td>{{ enrol.child_name }}</td>
                <td>{{ enrol.camp_name }}</td>
                <td>${{ enrol.fee - gift.avg }}</td>
              </tr>
            </table>
            <p v-for="i in 4" :key="i"><br></p>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDmkvSJs3" id="content8-a6">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-black-outline display-4" href="#" @click="back">
                <span class="mbri-arrow-prev mbr-iconfont mbr-iconfont-btn"></span>
                Back
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <bottom-space/>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
    <input-modal v-if="showInput" @close="closeInput">
      <div slot="header">
        <h3>Please input the amount of gift card you are going to use.</h3>
      </div>
      <div slot="body">
        <p>Available Gift Card/Voucher Amount: {{ gift.amount }}</p>
        <input type="text" class="form-control" v-model="gift.redeemed">
      </div>
    </input-modal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';

export default {
  data() {
    return {
      location_id: 0,
      selected_id: this.camp_id,
      gift: {
        code: '',
        amount: 0,
        redeemed: 0,
        avg: 0,
        email: ''
      },
      pay_amount: 0,
      checkout_busy: false,
      stripe_data: {
        stripeToken: null,
        stripeEmail: '',
        amount: 0,
        gift_used: 0
      },
      form: {
        busy: false
      },
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      },
      showInput: false
    }
  },
  computed: {
    ...mapGetters({
      isLoggedin: 'auth/check',
      parent: 'camps/parent',
      post: 'camps/post',
      enrols: 'camps/enrols'
    }),
    total_fee() {
      return this.payable_enrols.reduce((acc, item) => acc + item.fee - this.gift.avg, 0);
    },
    payable_enrols() {
      return this.enrols.filter(enrol => enrol.camp_id > 0);
    }
  },
  created() {
    if (!this.enrols.length || !this.children.length) {
      this.$router.push({name: 'camps.search'});
      return ;
    }
  },
  methods: {
    apply() {
      if (!this.gift.code) {
        this.showWarning('Please input your Gift Card/Voucher Code');
        return ;
      }
      this.form.busy = true;
      axios.post('/api/check-gift-card', {code: this.gift.code, email: this.parent.email}).then(response => {
        this.form.busy = false;
        if (response.data.valid) {
          this.gift.amount = response.data.amount;
          this.showInput = true;
        } else {
          this.showError('Invalid Gift Card/Voucher code');
        }
      }).catch(error => {
        this.form.busy = false;
      });
    },
    checkout() {
      if (this.total_fee === 0) {
        this.showWarning('Please check your payment fees');
        return ;
      }
      this.checkout_busy = true;
      this.pay_amount = this.total_fee * 100;
      axios.post('/api/check-payment', {enrols: this.payable_enrols}).then(response=> {
        if (!response.data.payed_list.length) {
          return axios.post('/api/stripe-publish-key')
        } else {
          this.checkout_busy = false
          var names = response.data.payed_list.reduce((acc, item) => acc + ', ' + item.child_name, '');
          var msg = 'The camp for following children is alreday payed, please remove them' + '<br><br>' + names.substring(1);
          this.showWarning(msg);
        }
      }).then(response => {
        if (response.data.success == true) {
          this.checkout_busy = false;
          this.createCardToken(response.data.key);
        } else {
          this.checkout_busy = false;
        }
      }).catch(error=>{
        this.checkout_busy = false
      })
    },
    createCardToken(pub_key) {
      this.$checkout.open({
        key: pub_key,
        name: 'Are you ready to pay?',
        currency: 'USD',
        amount: this.pay_amount,
        token: (token) => {
          this.createCharge(token);
        }
      });
    },
    createCharge(token) {
      this.checkout_busy = true;
      this.stripe_data.stripeToken = token.id;
      this.stripe_data.stripeEmail = token.email;
      this.stripe_data.amount = this.pay_amount;
      axios.post('/api/stripe-create-charge', this.stripe_data).then(response => {
        this.savePaymentDetail();
        this.checkout_busy = false;
      }).catch(error => {
        this.checkout_busy = false;
      })
    },
    savePaymentDetail() {
      this.checkout_busy = true;
      this.gift.email = this.parent.email;
      axios.post('/api/save-payment', {enrols: this.payable_enrols, gift: this.gift}).then(response => {
        return axios.post('/api/unsubscribe', {email: this.parent.email, list: 1});
      }).then(response => {
        return axios.post('/api/subscribe', {email: this.parent.email, list: 2});
      }).then(response => {
        return axios.post('/api/send-mail', {message: 'Successfully registered', to_email: this.parent.email});
      }).then(response => {
        return this.$store.dispatch('camps/removeEnrols');
      }).then(reponse => {
        this.$router.push({name: 'camps.success'});
        this.checkout_busy = false;
      }).catch(error => {
        console.log(error);
        this.checkout_busy = false;
      });
    },
    closeInput() {
      if (this.gift.redeemed > this.gift.amount) {
        let msg = 'The maximun amount of Gift Card/Voucher is ' + this.gift.amount;
        this.showWarning(msg);
        return ;
      }
      this.gift.avg = Math.floor(this.gift.redeemed / this.payable_enrols.length);
      this.showInput = false;
    },
    back() {
      this.$router.push({name: 'camps.select'});
    },
    showWarning(message) {
      this.msg.title = 'Warning!';
      this.msg.message = message;
      this.msg.type = 'warning'
      this.$refs.simplert.openSimplert(this.msg);
    },
    showError(message) {
      this.msg.title = 'Error'
      this.msg.message = message;
      this.msg.type = 'error'
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    color: #767676;
    text-align: center;
  }
  th, td {
    padding: 7px;
  }
</style>