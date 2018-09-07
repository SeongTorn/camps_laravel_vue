<template>
  <div>
    <section class="mbr-section info1 cid-qZCLOV2j2S" style="margin-top: 145px !important;">
      <form @submit.prevent="next" @keydown="form.onKeydown($event)">
      <div class="container">
        <div class="row justify-content-center content-row">
          <div class="media-container-column title col-12 col-lg-7 col-md-6">
            <h2 class="align-left mbr-bold mbr-fonts-style display-2">Parent Details</h2>
          </div>
          <div class="media-container-column col-12 col-lg-3 col-md-4">
            <div class="mbr-section-btn align-right py-4">
              <a class="btn btn-primary display-4" href="#" @click="login">
                <span class="mbrib-login mbr-iconfont mbr-iconfont-btn"></span>
                Log In
              </a>
            </div>
          </div>
          <div class="col-md-5" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">First Name*</label>
              <input type="text" class="form-control" v-model="form.first_name" name="first_name" required="" placeholder="Samantha" id="name-form1-9a">
            </div>
          </div>
          <div class="col-md-5" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Last Name*</label>
              <input type="text" class="form-control" v-model="form.last_name" name="last_name" required="" placeholder="Sample" id="name-form1-9a">
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Email Address*</label>
              <input type="email" class="form-control" v-model="form.email" name="email" required="" placeholder="samantha.sample@gmail.com" id="name-form1-9a">
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Phone Number*</label>
              <input type="text" class="form-control" v-model="form.phone" placeholder="0400 000 000" required="" id="name-form1-9a">
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <br>
              <span class="input-group-btn">
                <v-button :loading="form.busy" class="btn btn-form btn-primary display-4">
                  Next
                </v-button>
              </span>
              <br>
              <br>
              <br>
            </div>
          </div>
        </div>
      </div>
      </form>
    </section>
    <safety-space/>
    <bottom-space/>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';
import Form from 'vform'

export default {
  data() {
    return {
      form: new Form({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        busy: false
      }),
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      }
    }
  },
  computed: mapGetters({
    camps: 'camps/camps'
  }),
  created() {
    // console.log(this.location)
    // this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.location.id})
  },
  methods: {
    login(e) {
      e.preventDefault();
      this.$router.push({name: 'camps.login'});
    },
    next() {
      // console.log(this.form);
      this.form.busy = true;
      axios.post('/api/subscribe', {email: this.form.email, list: 1}).then(response => {
        // console.log(response.data);
        if (response.data.success == true) {
          return axios.post('/api/add-to-drip', {email: this.form.email})
        } else {
          this.msg.title = 'Subscribe Error';
          this.msg.message = 'Some error occured during subscribe action';
          this.showMessage();
          this.form.busy = false;
        }
      }).then(response => {
        // console.log(response.data);
        this.form.busy = false;
        if (response.data.success) {
          return this.$store.dispatch('camps/setParent', {parent: this.form})
        } else {
          this.msg.title = 'Drip Error';
          this.msg.message = 'Some error occured during add drip action';
          this.showMessage();
        }
      }).then(() => {
        this.$router.push({name: 'camps.register.parent2'});
      }).catch(error => {
        this.msg.title = 'Set Parent Info Error';
        this.msg.message = error;
        this.showMessage();
      });;
    },
    showMessage() {
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

