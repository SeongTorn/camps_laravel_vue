<template>
  <div>
    <section class="mbr-section info1 cid-qZCLOV2j2S" style="margin-top:145px !important">
      <div class="container">
        <form @submit.prevent="register">
        <div class="row justify-content-center content-row">
          <div class="media-container-column title col-12 col-lg-7 col-md-6">
            <h2 class="align-left mbr-bold mbr-fonts-style display-2">Parent Details</h2>
          </div>
          <div class="media-container-column col-12 col-lg-3 col-md-4">
            <div class="mbr-section-btn align-right py-4"><a class="btn btn-primary display-4" href="https://learncode.com.au/camps/login/"><span class="mbrib-login mbr-iconfont mbr-iconfont-btn"></span>Log In</a></div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Postcode*</label>
              <input type="text" class="form-control" v-model="form.postcode" required="" placeholder="2000">
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Backup Emergency Contact*</label>
              <input type="text" class="form-control" v-model="form.emergency_contact" required="" placeholder="E.g. John Smith 0400 000 000">
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">How did you find out about us?*</label>
              <select class="form-control" v-model="form.heard_about" required>
                <option value="" disabled selected>-- Please Select --</option>
                <option v-for="item in heard_options" :value="item.value" :key="item.value">{{ item.name }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-10" data-for="name">
            <div class="form-group">
              <input type="checkbox" v-model="form.photos_permitted">
              I consent to have photos and/or videos taken of my children in accordance with our
              <a href="https://learncode.com.au/blog/media-policy/">media policy.</a>
              (optional)<br>

              <input type="checkbox" v-model="agree">
              I agree with our
              <a href="https://learncode.com.au/blog/terms-and-conditions/">terms and conditions</a>
              .<br><br>

              <span class="input-group-btn">
                <button class="btn btn-primary btn-form display-4" :disabled="!agree">Next</button>
              </span>
              <br>
              <br>
              <br>
            </div>
          </div>
        </div>
        </form>
      </div>
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
        emergency_contact: '',
        postcode: '',
        heard_about: 0,
        photos_permitted: true
      },
      agree: false,
      heard_options: [
        {value: 1, name: 'Saab'},
        {value: 2, name: 'Mercedes'},
        {value: 3, name: 'Audi'}
      ]
    }
  },
  computed: mapGetters({
    parent: 'camp/parent'
  }),
  created() {

  },
  methods: {
    register() {
      this.form = Object.assign({}, this.form, this.parent)
      axios.post('/api/register-parent', this.form).then(response => {
        this.$store.dispatch('camp/setParent', {parent: response.data});
        this.$router.push({name: 'camp.child-details'})
      }).catch(error => {
        console.error(error.message);
      });
    }
  }
}
</script>

