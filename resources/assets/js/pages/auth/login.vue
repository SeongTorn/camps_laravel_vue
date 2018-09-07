<template>
  <div>
    <top-space>
      <div slot="title"><br><br>Log In</div>
      <div slot="sub-title">Enrol Faster &amp; Easier</div>
    </top-space>

    <form @submit.prevent="login" @keydown="form.onKeydown($event)">
    <section class="mbr-section article content1 cid-qZDAwC1GdG" id="content1-aw">
			<div>
				<div class="media-container-row">
					<div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
						<div class="col-md-12" data-for="name">
							<div class="form-group">
								<label class="form-control-label mbr-fonts-style display-7" >Email*</label>
								<input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" required="" placeholder="example@example.com">
							</div>
						</div>
						<div class="col-md-12" data-for="name">
							<div class="form-group">
								<label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Password*</label>
								<input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" required>
							</div>
						</div>
						<div class="col-md-12">
              <router-link :to="{ name: 'camps.password.request' }" class="small ml-auto my-auto">
                I have forgotten my password !
              </router-link>
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
								Login
              </v-button>
						</div>
					</div>
				</div>
			</div>
		</section>
    </form>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    msg: {
      title: 'Alert Title',
      message: 'Alert Message',
      type: 'error'
    },
    remember: false
  }),
  computed: mapGetters({
    user: 'auth/user',
    parent: 'camps/parent'
  }),
  methods: {
    async login () {
      this.form.post('/api/login').then(response => {
        return this.$store.dispatch('auth/saveToken', {
          token: response.data.token,
          remember: this.remember
        })
      }).then(() => {
        this.login_success()
      }).catch(error => {
        this.msg.title = 'Login Failed';
        this.msg.message = 'These credentials do not match our records.';
        this.showMessage();
      })
    },
    async login_success() {
      await this.$store.dispatch('auth/fetchUser')
      console.log(this.user);
      await this.$store.dispatch('camps/fetchParent', {email: this. user.email})
      console.log(this.parent);
      this.$router.push({name: 'camps.all-children'});
    },
    showMessage() {
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>
