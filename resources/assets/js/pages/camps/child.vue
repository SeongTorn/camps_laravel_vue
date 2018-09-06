<template>
  <div>
    <top-space/>
    <top-space/>
    <section class="mbr-section info1 cid-qZCLOV2j2S">
      <form @submit.prevent="register">
      <div class="container">
        <div class="row justify-content-center content-row">
          <div class="media-container-column col-12 col-lg-3 col-md-4">
            <div class="mbr-section-btn align-right py-4"></div>
          </div>
          <div class="col-md-12" data-for="name">
            <h2 class="align-center mbr-bold mbr-fonts-style display-2">Child Details</h2><br>
          </div>
          <div class="col-md-6" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">First Name*</label>
              <input type="text" class="form-control" v-model="form.first_name" required="" placeholder="Johnny">
            </div>
          </div>
          <div class="col-md-6" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Last Name*</label>
              <input type="text" class="form-control" v-model="form.last_name" required="" placeholder="Sample">
            </div>
          </div>
          <div class="col-md-12" data-for="name">
            <label class="form-control-label mbr-fonts-style display-7">Date of Birth:*</label>
          </div>
          <div class="col-md-4" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Day*</label>
              <select class="form-control" v-model="form.birth_day" required="">
                <option value="" disabled>--- Please Select ---</option>
                <option v-for="d in 31" :key="d" :value="d">{{ d }}st</option>
              </select>
            </div>
          </div>
          <div class="col-md-4" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Month*</label>
              <select class="form-control" v-model="form.birth_month" required="">
                <option value="" disabled>--- Please Select ---</option>
                <option v-for="(month, index) in month_names" :key="index" :value="index+1">{{ month }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-4" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Year*</label>
              <select class="form-control" v-model="form.birth_year" required="">
                <option value="" disabled>--- Please Select ---</option>
                <option v-for="y in new Date().getFullYear()"  v-if="y >= 2000" :key="y" :value="y">{{ y }}</option>
                @endfor
              </select>
            </div>
          </div>
          <div class="col-md-12" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">School*</label>
              <input type="text" class="form-control" v-model="form.school" required="" placeholder="Sample Public School">
            </div>
          </div>
          <div class="col-md-12" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Allergies (if applicable)</label>
              <textarea class="form-control" rows="2" v-model="form.allergies"></textarea>
            </div>
          </div>
          <div class="col-md-12" data-for="name">
            <div class="form-group">
              <label class="form-control-label mbr-fonts-style display-7">Learning Difficulties (if applicable)</label>
              <textarea class="form-control" rows="2" v-model="form.learning_difficulties"></textarea>
            </div>
          </div>
          <div class="col-md-12" data-for="name">
            <div class="form-group">
              <br>
              <span class="input-group-btn">
                <v-button class="btn btn-form btn-primary display-4" :loading="form.busy">
                  Next
                </v-button>
              </span>
              <br><br><br>
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
      form: {
        first_name: '',
        last_name: '',
        birth_day: '',
        birth_month: '',
        birth_year: '',
        school: '',
        allergies: '',
        learning_difficulties: '',
        busy: false
      },
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      },
      month_names: ["January", "February", "March", "April", "May","June","July", "August", "September", "October", "November","December"]
    }
  },
  computed: mapGetters({
    parent: 'camps/parent'
  }),
  created() {
    // console.log(this.parent)
    // this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.location.id})
  },
  methods: {
    register() {
      this.form.busy = true;
      this.form = Object.assign({}, this.form, {parent_id: this.parent.id})
      axios.post('/api/register-child', this.form).then(response => {
        this.$store.dispatch('camps/addChildren', {child: response.data}).then(() => {
          this.form.busy = false;
          this.$router.push({name: 'camps.all-children'})
        }).catch(error => {
          this.form.busy = false;
          this.msg.title = 'Add Children Error';
          this.msg.message = error;
          this.showMessage();
        });
      }).catch(error => {
        this.form.busy = false;
        this.msg.title = 'API call error';
        this.msg.message = 'Error occured in calling api';
        this.showMessage();
      });
    },
    showMessage() {
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

