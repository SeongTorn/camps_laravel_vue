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
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-secondary display-5" href="#" @click="checkout">
                <span class="mbri-credit-card mbr-iconfont mbr-iconfont-btn"></span>
                Pay Now
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section article content1 cid-qZDljz1dnu">
      <form @submit.prevent="apply" @keydown="form.onKeydown($event)">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-4 mbr-fonts-style display-7">
            <p>Gift Card/Voucher Code:</p>
            <input type="text" class="form-control" v-model="gift_code">
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
              <tr v-for="(enrol, index) in enrols" :key="index">
                <td>{{ enrol.child_name }}</td>
                <td>{{ enrol.camp_name }}</td>
                <td>${{ enrol.fee }}</td>
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
      gift_code: '',
      gift_amount: 0,
      pay_amount: 0,
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
      }
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
      return this.enrols.reduce((acc, item) => acc + item.fee, 0);
    }
  },
  created() {
    console.log(this.enrols);
    // this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.post.id})
  },
  methods: {
    apply() {
      if (!this.gift_code) {
        this.msg.title = 'Warning';
        this.msg.type = 'warning';
        this.msg.message = 'Please input your Gift Card/Voucher Code';
        this.showMessage();
        return ;
      }
      this.form.busy = true;
      axios.post('/api/check-gift-card', {code: this.gift_code, email: this.parent.email}).then(response => {
        this.form.busy = false;
        if (response.data.valid) {
          this.gift_amount = response.data.amount;
        } else {
          this.msg.title = 'Error';
          this.msg.message = 'Invalid Gift Card/Voucher code';
          this.showMessage();
        }
      }).catch(error => {
        this.form.busy = false;
        console.error(error.message);
      });
    },
    checkout(e) {
      e.preventDefault();
      this.pay_amount = (this.total_fee - this.gift_amount) * 100;
      axios.post('/api/stripe-publish-key').then(response => {
        if (response.data.success == true) {
          console.log(response.data.key);
          this.createCardToken(response.data.key);
        } else {
          console.log('failed to get stripe publish key');
        }
      });
    },
    createCardToken(pub_key) {
      this.$checkout.open({
        key: pub_key,
        name: 'Are you ready to pay?',
        currency: 'USD',
        amount: this.pay_amount,
        token: (token) => {
          console.log(token);
          this.createCharge(token);
        }
      });
    },
    createCharge(token) {
      this.stripe_data.stripeToken = token.id;
      this.stripe_data.stripeEmail = token.email;
      this.stripe_data.amount = this.pay_amount;
      axios.post('/api/stripe-create-charge', this.stripe_data).then(response => {
        console.log(response.data);
        this.savePaymentDetail();
      }).catch(error => {
        console.log(error.message);
      })
    },
    savePaymentDetail() {
      this.$router.push({name: 'camps.success'});
      axios.post('/api/save-payment', {data: this.enrol}).then(response => {
        console.log(response.data);
        if (response.data.success == true) {

        }
      })
    },
    back() {
      this.$router.push({name: 'camps.select'});
    },
    showMessage() {
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