<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Forgot Password</div>
    </top-space>

    <form @submit.prevent="send" @keydown="form.onKeydown($event)">
    <section class="mbr-section article content1 cid-qZDAwC1GdG" id="content1-aw">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
            <div class="col-md-12" data-for="name">
              <div class="form-group">
                <label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Enter Email*</label>
                <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" required placeholder='example@example.com'>
                <has-error :form="form" field="email"/>
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
                <span class="mbrib-login mbr-iconfont mbr-iconfont-btn"></span>
                Send Reset Link
              </v-button>
            </div>
          </div>
        </div>
      </div>
    </section>
    </form>
    <bottom-space/>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
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
    msg: {
      title: 'Alert Title',
      message: 'Alert Message',
      type: 'info'
    },
    form: new Form({
      email: ''
    })
  }),
  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')
      this.status = data.status
      this.showMessage(data.status)
      this.form.reset()
    },
    showMessage(message) {
      this.msg.title = 'Success!';
      this.msg.message = message;
      this.msg.type = 'info';
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>