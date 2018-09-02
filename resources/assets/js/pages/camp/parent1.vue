<template>
  <div>
    <section class="mbr-section info1 cid-qZCLOV2j2S" style="margin-top: 145px !important;">
      <form @submit.prevent="next">
      <div class="container">
        <div class="row justify-content-center content-row">
          <div class="media-container-column title col-12 col-lg-7 col-md-6">
            <h2 class="align-left mbr-bold mbr-fonts-style display-2">Parent Details</h2>
          </div>
          <div class="media-container-column col-12 col-lg-3 col-md-4">
            <div class="mbr-section-btn align-right py-4">
              <a class="btn btn-primary display-4" href="#">
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
                <button class="btn btn-primary btn-form display-4">Next</button>
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        email: '',
        phone: ''
      }
    }
  },
  computed: mapGetters({
    location: 'camp/location',
    camps: 'camp/camps'
  }),
  created() {
    // console.log(this.location)
    // this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.location.id})
  },
  methods: {
    next() {
      console.log(this.form);
      this.$store.dispatch('camp/setParent', {parent: this.form});
      this.$router.push({name: 'camp.register.parent2'});
    }
  }
}
</script>

