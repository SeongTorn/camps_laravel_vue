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
								<label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Email*</label>
								<input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" required="" placeholder="example@example.com" id="name-form1-9a-1">
                <has-error :form="form" field="email"/>
                <!--<input type="email" class="form-control" name="name" data-form-field="Name" required="" placeholder="example@example.com" id="name-form1-9a-1"> -->
							</div>
						</div>
						<div class="col-md-12" data-for="name">
							<div class="form-group">
								<label class="form-control-label mbr-fonts-style display-7" for="name-form1-9a">Password*</label>
								<input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
                <has-error :form="form" field="password"/>
								<!--<input type="password" class="form-control" name="name" data-form-field="Name" required="" placeholder="Password" id="name-form1-9a-2"> -->
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
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({name: 'camps.all-children'});
    }
  }
}
</script>
