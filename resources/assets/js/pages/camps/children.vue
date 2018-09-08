<template>
  <div v-if="children">
    <section class="mbr-section content4 cid-qRjMf7pfgj">
      <div class="container">
        <div class="media-container-row">
          <div class="title col-12 col-md-8">
            <h2 class="align-center pb-3 mbr-fonts-style display-2"><br>Your Children</h2>
            <h3 class="mbr-section-subtitle align-center mbr-light mbr-fonts-style display-5">
              Review the list of children you wish to register. You can choose which camps to register them for in the next step
            </h3>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section article content1 cid-qRjMyVUXxD" id="content1-55">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-5">
            <div v-for="child in children" :key="child.id">
              <p><strong>{{ child.first_name }}</strong> <small><a href="#" @click="remove(child.id)">(remove)</a></small></p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section content8 cid-qRjM9jLcC8" id="content8-52">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a href="#" class="btn btn-md btn-black-outline display-4" @click="addchild">
                <span class="mbri-user mbr-iconfont mbr-iconfont-btn"></span>ADD ANOTHER CHILD
              </a>
              <a href="#" class="btn btn-md btn-primary display-4" @click="next">
                <span class="mbrib-rocket mbr-iconfont mbr-iconfont-bt"></span>NEXT: SELECT CAMPS
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <bottom-space/>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';

export default {
  data() {
    return {
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      }
    }
  },
  computed: mapGetters({
    parent: 'camps/parent',
    children: 'camps/children',
    camp_id: 'camps/camp_id',
    isLoggedin: 'auth/check'
  }),
  created() {
    if (this.isLoggedin) {
      this.$store.dispatch('camps/fetchChildren', {parent_id: this.parent.id});
    }
  },
  methods: {
    remove(id) {
      axios.post('/api/deactive-child', {id: id}).then(response => {
        if (response.data.status == "success") {
          this.$store.dispatch('camps/removeChild', {child_id: id});
        }
      }).catch(error => {
        console.error(error.message);
      });
    },
    addchild() {
      this.$router.push({name: 'camps.child-details'});
    },
    next() {
      if (!this.parent) {
        this.showWarning('No parent. Please register or login to parent');
        return ;
      }
      if (this.children.length == 0) {
        this.showWarning('No children to register. Please add child');
        return ;
      }
      this.$store.dispatch('camps/initEnrols', {camp_id: this.camp_id}).then(() => {
        this.$router.push({name: 'camps.select'});
      }).catch(error => {
        console.log(error);
      });
    },
    showWarning(message) {
      this.msg.message= 'No children to register. Please add child',
      this.msg.title  = 'Warning';
      this.msg.type = 'warning';
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

