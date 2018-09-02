<template>
  <div>
    <div class="mbr-section info1 cid-qQXqFHEWew">
      <div class="container">
        <div class="row justify-content-center content-row">
          <div class="media-container-column title col-12 col-lg-7 col-md-6">
            <h3 class="mbr-section-subtitle align-left mbr-light pb-3 mbr-fonts-style display-5">To find a CodeSpace Camp near you</h3>
            <h2 class="align-left mbr-bold mbr-fonts-style display-2">Enter your postcode or suburb<br></h2>
            <br>
            <vue-bootstrap-typeahead
              v-model="post_code"
              :data="post_codes"
              :serializer="s => s.text"
              placeholder="Enter Suburb Here..."
              @hit="sel_post = $event" />
            <br>
            <button href="" type="submit" class="btn btn-form btn-primary display-4" @click="search">Search</button>
          </div>
          <div class="media-container-column col-12 col-lg-3 col-md-4">
          </div>
        </div>
      </div>
    </div>
    <bottom-space/>
  </div>
</template>

<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import axios from 'axios';
import _ from 'lodash'

export default {
  components: {
    VueBootstrapTypeahead
  },
  metaInfo () {
    return { title: this.$t('Search') }
  },
  data: () => ({
    post_code: '',
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
    search() {
      this.$store.dispatch('camp/setLocation', {location: this.sel_post})
      this.$router.push({name: 'camp.results'})
    }
  },
  watch: {
    post_code: _.debounce(function(value) { this.getPostCodes(value) }, 500)
  }
}
</script>

<style>
  .list-group-item.active {
    background-color: #f5f5f5 !important;
    color: #565656 !important;
  }
</style>

