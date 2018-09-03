<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Almost There...</div>
    </top-space>

    <section class="mbr-section article content1 cid-qZDljz1dnu" id="content1-a5">
      <div class="mbr-text col-12 col-md-12 mbr-fonts-style display-7">
        <h2>TOTAL: ${{ pay_amount }}</h2>
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
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-4 mbr-fonts-style display-7">
            <p>Gift Card/Voucher Code:</p>
            <input type="text" class="form-control" v-model="gift_code">
            <a href="" class="btn btn-primary btn-form display-4" @click="applyGiftCard">Apply</a>
            <p><br></p>
            <p><br></p>
          </div>
        </div>
      </div>
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
              <tr>
                <td>Joshua Brooks</td>
                <td>Game Making Mania - The Ponds High School</td>
                <td>$99</td>
              </tr>
              <tr>
                <td></td>
                <td>Minecraft Modding Mayhem - The Ponds High School</td>
                <td>$99</td>
              </tr>
              <tr>
                <td>Lachlan Brooks</td>
                <td>Minecraft Modding Mayhem - The Ponds High School</td>
                <td>$99</td>
              </tr>
            </table>
            <p><br></p>
            <p><br></p>
            <p><br></p>
            <p><br></p>
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
      pay_amount: 200,
      stripe_data: {
        stripeToken: null,
        stripeEmail: '',
        amount: 0,
        gift_used: 0
      }
    }
  },
  computed: {
    ...mapGetters({
      isLoggedin: 'auth/check',
      parent: 'camps/parent',
      camp_id: 'camps/camp_id',
      post: 'camps/post'
    }),
    cur_camp() {

    },
  },
  created() {
    // this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.post.id})
  },
  methods: {
    applyGiftCard(e) {
      e.preventDefault();
      axios.post('/api/check-gift-card', {code: this.gift_code, email: this.parent.email}).then(response => {
        if (response.data.valid) {
          this.gift_amount = response.data.amount;
          this.pay_amount -= this.gift_amount;
        } else {
          console.log('invalid gift code');
        }
      }).catch(error => {
        console.error(error.message);
      });
    },
    checkout(e) {
      e.preventDefault();
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
    },
    back() {
      this.$router.push({name: 'camps.select'});
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