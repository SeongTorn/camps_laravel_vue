<template>
  <div>
    <div class="mbr-section info1 cid-qQXqFHEWew">
      <div class="container">
        <form @submit.prevent="search" @keydown="form.onKeydown($event)">
        <div class="row justify-content-center content-row">
          <div class="media-container-column title col-12 col-lg-7 col-md-6">
            <h3 class="mbr-section-subtitle align-left mbr-light pb-3 mbr-fonts-style display-5">To find a CodeSpace Camp near you</h3>
            <h2 class="align-left mbr-bold mbr-fonts-style display-2">Enter your postcode or suburb<br></h2>
            <br>
            <vue-bootstrap-typeahead
              v-model="form.post_code"
              :data="post_codes"
              :serializer="s => s.text"
              placeholder="Enter Suburb Here..."
              @hit="sel_post = $event" />
            <br>
            <v-button :loading="form.busy" class="btn btn-form btn-primary display-4">
              Search
            </v-button>
          </div>
          <div class="media-container-column col-12 col-lg-3 col-md-4">
          </div>
        </div>
        </form>
      </div>
    </div>
    <bottom-space/>
  </div>
</template>

<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import axios from 'axios';
import Form from 'vform'
import _ from 'lodash'

export default {
  components: {
    VueBootstrapTypeahead
  },
  metaInfo () {
    return { title: this.$t('Search') }
  },
  data: () => ({
    form: new Form({
      post_code: ''
    }),
    post_codes: [],
    sel_post: null
  }),
  methods: {
    async getPostCodes(query) {
      axios.post('/api/postcodes', {term: query}).then(response => {
        this.post_codes = response.data;
      }).catch(error => {
        console.error(error.message);
      });
    },
    search(e) {
      e.preventDefault();
      this.$store.dispatch('camps/initChildren');
      this.$store.dispatch('camps/setPost', {post: this.sel_post}).then(() => {
        this.$router.push({name: 'camps.results'});
      });
    }
  },
  watch: {
    'form.post_code': _.debounce(function(value) { this.getPostCodes(value) }, 200)
  }
}
</script>

<style>
  .list-group-item.active {
    background-color: #f5f5f5 !important;
    color: #565656 !important;
    border-color: #f1f1f1 !important;
  }
</style>

