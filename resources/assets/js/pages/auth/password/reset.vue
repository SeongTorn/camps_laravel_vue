<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Reset Password</div>
    </top-space>

    <form @submit.prevent="reset" @keydown="form.onKeydown($event)">
    <section class="mbr-section article content1 cid-qZDAwC1GdG" id="content1-aw">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
            <div class="col-md-12" data-for="name">
              <div class="form-group">
                <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Email*</label>
                <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" readonly>
                <has-error :form="form" field="email"/>
              </div>
            </div>
            <div class="col-md-12" data-for="name">
              <div class="form-group">
                <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Password*</label>
                <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password" required="" placeholder="Password">
                <has-error :form="form" field="password"/>
              </div>
            </div>
            <div class="col-md-12" data-for="name">
              <div class="form-group">
                <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Confirm*</label>
                <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation" required="" placeholder="Confirm Password">
                <has-error :form="form" field="password_confirmation"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDAwCi9Op" id="content8-ax">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <v-button :loading="form.busy" class="btn btn-secondary display-4 btn-radius">
                <span class="mbrib-login mbr-iconfont mbr-iconfont-btn"></span>Reset
              </v-button>
            </div>
          </div>
        </div>
      </div>
    </section>
    </form>
    <bottom-space/>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),

  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    async reset () {
      const { data } = await this.form.post('/api/password/reset')
      this.status = data.status
      this.form.reset()
    }
  }
}
</script>
