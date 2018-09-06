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

              <v-button class="btn btn-form btn-primary display-4" :loading="form.busy" :disabled="!agree">
                Next
              </v-button>
              <br v-for="i in 3" :key="i">
            </div>
          </div>
        </div>
        </form>
      </div>
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
      form: {
        emergency_contact: '',
        postcode: '',
        heard_about: 0,
        photos_permitted: true,
        busy: false
      },
      agree: false,
      heard_options: [
        {value: 1, name: 'Saab'},
        {value: 2, name: 'Mercedes'},
        {value: 3, name: 'Audi'}
      ],
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      }
    }
  },
  computed: mapGetters({
    parent: 'camps/parent'
  }),
  created() {

  },
  methods: {
    register() {
      this.form.busy = true;
      this.form = Object.assign({}, this.form, this.parent)
      axios.post('/api/register-parent', this.form).then(response => {
        this.$store.dispatch('camps/setParent', {parent: response.data}).then(() => {
          this.form.busy = false;
          this.$router.push({name: 'camps.child-details'})
        }).catch(error => {
          this.form.busy = false;
          this.msg.title = 'Set Parent Info Error';
          this.msg.message = error;
          this.showMessage();
        });

      }).catch(error => {
        this.msg.title = 'Set Parent Info Error';
        this.msg.message = 'Error in call api to register parent details';
        this.showMessage();
        this.form.busy = false;
      });
    },
    showMessage() {
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

