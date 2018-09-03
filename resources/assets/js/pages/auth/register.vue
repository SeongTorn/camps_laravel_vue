<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Please Set a Password</h2></div>
    </top-space>

    <form @submit.prevent="register" @keydown="form.onKeydown($event)">
    <section class="mbr-section article content1 cid-qZDtdryILi" id="content1-ah">
      <div class="container">
        <p>Create Password:</p>
        <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password" required placeholder="Password...">
        <has-error :form="form" field="password"/><br>
        <p>Confirm Password:</p>
        <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation" required placeholder="Confirm Password...">
        <has-error :form="form" field="password_confirmation"/>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDtBkSJ6o" id="content8-an">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <v-button :loading="form.busy" class="btn btn-secondary display-4 btn-radius">
                <span class="mbrib-login mbr-iconfont mbr-iconfont-btn"></span>Next
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
  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async register () {
      // Register the user.
      const { data } = await this.form.post('/api/register')
      // Log in the user.
      //const { data: { token } } = await this.form.post('/api/login')
      // Save the token.
      //this.$store.dispatch('auth/saveToken', { token })
      // Update the user.
      //await this.$store.dispatch('auth/updateUser', { user: data })
      // Redirect home.
      this.$router.push({ name: 'camps.all-children' })
    }
  }
}
</script>
