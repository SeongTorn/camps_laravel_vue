<template>
  <div>
    <section class="header12 cid-qRjJpeGsr6 mbr-fullscreen mbr-parallax-background" id="header12-4v">
      <div class="mbr-overlay" style="opacity: 0.9; background-color: rgb(70, 80, 82);">
      </div>

      <div class="container  ">
        <div class="media-container">
          <div class="col-md-12 align-center">
            <h1 class="mbr-section-title pb-3 mbr-white mbr-bold mbr-fonts-style display-1"><br>...AND LIFTOFF</h1>
            <p class="mbr-text pb-3 mbr-white mbr-fonts-style display-5">You've successfully registered.</p>
            <div class="mbr-section-btn align-center py-2"><a class="btn btn-md btn-secondary display-4" href="page11.html#content4-7u">Continue</a></div>

            <div class="icons-media-container mbr-white">
              <div class="card col-12 col-md-6 col-lg-4">
                <div class="icon-block">
                  <a href="#"><span class="mbr-iconfont mbri-cash"></span></a>
                </div>
                <h5 class="mbr-fonts-style display-5">Payment Complete</h5>
              </div>

              <div class="card col-12 col-md-6 col-lg-4">
                <div class="icon-block">
                  <a href="#"><span class="mbr-iconfont mbri-success"></span></a>
                </div>
                <h5 class="mbr-fonts-style display-5">Enrolment Completed</h5>
              </div>

              <div class="card col-12 col-md-6 col-lg-4">
                <div class="icon-block">
                  <a href="#"><span class="mbr-iconfont mbrib-paper-plane"></span></a>
                </div>
                <h5 class="mbr-fonts-style display-5">Confirmation Email Sent</h5>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mbr-arrow hidden-sm-down" aria-hidden="true">
        <a href="#next"><i class="mbri-down mbr-iconfont"></i></a>
      </div>
    </section>

    <section class="mbr-section form3 cid-qZDEpBnhyq" id="form3-bc">
      <div class="container">
        <div class="row justify-content-center">
          <div class="title col-12 col-lg-8">
            <h2 class="align-center pb-2 mbr-fonts-style display-2">We sent an email to joshua@learncode.com.au</h2>
            <h3 class="mbr-section-subtitle align-center pb-5 mbr-light mbr-fonts-style display-5">Didn't get an email? Check your spam folder or re-send the email below.</h3>
          </div>
        </div>

        <div class="row py-2 justify-content-center">
          <div class="col-12 col-lg-6  col-md-8 " data-form-type="formoid">
            <div data-form-alert="" hidden="">
              Thanks for filling out the form!
            </div>
            <form @submit.prevent="resend">
              <div class="mbr-subscribe input-group">
                <input class="form-control" type="email" v-model="form.new_email" placeholder="Email" required="">
                <span class="input-group-btn">
                  <v-button :loading="form.busy" class="btn btn-primary display-4 btn-radius">
                    Re-Send Email
                  </v-button>
                </span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <bottom-space/>

    <section class="mbr-section content8 cid-qYvMlMh459" id="content8-7v">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-primary display-4" href="https://learncode.com.au">
                <span class="mbrib-cursor-click mbr-iconfont mbr-iconfont-btn"></span>
                Return to Site
              </a>
              <a class="btn btn-secondary display-4" href="https://learncode.com.au/camps/">
                <span class="mbri-rocket mbr-iconfont mbr-iconfont-btn"></span>
                Find a Camp
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
      form: {
        email: '',
        new_email: '',
        list: 2,
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
      parent: 'camps/parent'
    })
  },
  created() {
    console.log(this.parent);
    this.form.email = this.parent.email;
  },
  methods: {
    resend() {
      axios.post('/api/send-mail', {message: 'test', toEmail: 'houn.sockram@hotmail.com'}).then(response => {
        return axios.post('/api/update-email', this.form);
      }).then(reponse => {
        return axios.post('api/update-from-drip', this.form);
      }).then(response => {
        return axios.post('/api/update-subscribe', this.form);
      }).then(response => {
        this.msg.title = 'Success!';
        this.msg.message = 'Successfully updated';
        this.msg.type = 'info';
        this.showMessage();
      })
      this.form.busy = true;
    }
  },
  showMessage() {
    this.$refs.simplert.openSimplert(this.msg);
  }
}
</script>

<style>
  .card-wrapper {
    display: table;
  }
  .camp-list {
    justify-content: flex-start !important;
  }
</style>