<template>
  <div v-if="camps">
    <top-space/>
    <top-space>
      <div slot="title">Camps for {{ cur_enrol_id >= 0 ? enrols[cur_enrol_id].child_name : '' }}</div>
      <div slot="sub-title">What camps do you want to register for?</div>
    </top-space>

    <section class="mbr-section article content1 cid-qRjNQCoqtg" id="content1-57">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-5">
            <p><strong>Select a Location:</strong></p>
            <p>
              <select ref="select_location" class="form-control" :value="this.location_id ? this.location_id : (this.cur_camp.location ? this.cur_camp.location.id : 0)" required="" @change="changeLocation">
                <option value="0" disabled>--- Please Select ---</option>
                <option v-for="(camp_list, index) in camps" :key="index" :value="camp_list.location.id">{{ camp_list.location.name }}</option>
              </select>
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="features3 cid-qQWAzjWbWN">
      <div class="container">
        <div class="media-container-row camp-list">
          <div v-for="camp in location_camps" :key="camp.id" class="card p-3 col-12 col-md-6 col-lg-4">
            <div class="card-wrapper">
              <div class="card-img">
                <img src="/assets/images/file-27-624x465.png">
              </div>
              <div class="card-box">
                <h4 class="card-title mbr-fonts-style display-7">{{ camp.topic }}</h4>
                <p class="mbr-text mbr-fonts-style display-7">{{ camp.topicDesc }}<br><br></p>
                <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-calendar">&nbsp;</span> {{ camp.start_date }} ({{ camp.length }})</p>
                <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-clock">&nbsp;</span> {{ camp.startTime }} - {{ camp.endTime }} with drop off from {{ camp.arriveTime }} and pickup until {{ camp.departTime }}</p>
                <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-pin">&nbsp;</span>{{ camp.name }}</p>
                <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-user">&nbsp;</span> Ages {{ camp.ages }}</p>
                <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-sale">&nbsp;</span> ${{ camp.price }}</p>
              </div>
              <div class="mbr-section-btn text-center">
                <a v-if="registered[camp.id]" href="#" class="btn btn-danger display-4">
                  Already Registered
                </a>
                <a v-else-if="selected_id ? selected_id == camp.id : cur_camp.camp.id == camp.id" href="#" class="btn btn-secondary display-4" @click="selectCamp($event, camp.id)">
                  <span class="mbrib-success mbr-iconfont mbr-iconfont-btn"></span>
                  SELECTED
                </a>
                <a v-else-if="camp.class_capacity - camp.sold > 0" href="#" class="btn btn-primary display-4" @click="selectCamp($event, camp.id)">
                  SELECT
                </a>
                <a v-else href="#" class="btn btn-danger display-4">
                  <span class="mbri-close mbr-iconfont mbr-iconfont-btn"></span>
                  SOLD OUT
                </a>
              </div>
              <br>
              <br>
              <div class="alert">
                <strong>	&#9888; In High Demand!</strong>
                This camp only has {{ camp.class_capacity - camp.sold > 9 ? 9 : camp.class_capacity - camp.sold }} spaces available.
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section content8 cid-qRjNQCA8gv" id="content8-58">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-black-outline display-4" href="#" @click="back">
                <span class="mbri-left mbr-iconfont mbr-iconfont-btn"></span>
                BACK
              </a>
              <a class="btn btn-primary display-4" href="#" @click="next">
                <span class="mbrib-rocket mbr-iconfont mbr-iconfont-btn"></span>
                NEXT
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
      location_id: 0,
      selected_id: 0,
      enrol_data: {
        id: -1,
        child_id: 0,
        camp_id: 0,
        camp_name: '',
        fee: 0
      },
      msg: {
        title: 'Alert Title',
        message: 'Alert Message',
        type: 'error'
      },
      camp_registered: []
    }
  },
  computed: {
    ...mapGetters({
      post: 'camps/post',
      camps: 'camps/camps',
      enrols: 'camps/enrols',
      children: 'camps/children'
    }),
    cur_camp() {
      let me = this;
      let camp_id = this.selected_id;
      let item = this.camps.find(function(item){
        let camp = item.camps.find(function(item){
          return item.id == camp_id;
        })
        return !!camp;
      });
      let camp = item.camps.find(function(camp){
        return camp.id == camp_id;
      })
      return {location: item.location, camp: camp};
    },
    location_camps() {
      let loc_id = this.location_id ? this.location_id : this.cur_camp.location.id;
      let filter_arr = this.camps.filter(item => {
        return item.location.id == loc_id;
      });
      if (filter_arr.length > 0) {
        filter_arr[0].camps.filter(camp => {
          this.check_register(camp.id);
          return true;
        })
        return filter_arr[0].camps;
      }
      return [];
    },
    cur_enrol_id() {
      let enrol_id = -1;
      this.enrols.filter((item, index) => {
        if (item.selecting) {
          enrol_id = index;
        }
        return true;
      })
      return enrol_id;
    },
    registered() {
      console.log(this.camp_registered)
      return this.camp_registered;
    },
  },
  created() {
    this.selected_id = this.enrols[this.cur_enrol_id].camp_id;
    this.$store.dispatch('camps/fetchLocationCamps', {post_id: this.post.id})
  },
  methods: {
    changeLocation(e) {
      this.location_id = e.target.value;
    },
    selectCamp(e, id) {
      e.preventDefault();
      this.selected_id = id;
    },
    check_register(camp_id) {
      axios.post('/api/check-registered', {
        camp_id: camp_id,
        child_id: this.enrols[this.cur_enrol_id].child_id
      }).then(response => {
        console.log(response.data.status)
        this.camp_registered[camp_id] = response.data.status;
      });
    },
    next() {
      if (this.registered[this.selected_id]) {
        this.showWarning('No camps for this child');
        return ;
      }
      this.enrol_data.id = this.cur_enrol_id;
      this.enrol_data.camp_id = this.selected_id;
      this.enrol_data.fee = this.cur_camp.camp.price;
      this.enrol_data.camp_name = this.cur_camp.camp.name;
      this.enrol_data.next_id = this.cur_enrol_id < this.enrols.length - 1 ? this.cur_enrol_id + 1 : this.cur_enrol_id;
      this.$store.dispatch('camps/setEnrol', {enrol: this.enrol_data}).then(response => {
        if (this.enrol_data.next_id == this.enrol_data.id) {
          this.$router.push({name: 'camps.payment'});
        } else {
          this.selected_id = this.enrols[this.enrol_data.next_id].camp_id ? this.enrols[this.enrol_data.next_id].camp_id : this.selected_id;
          this.location_id = this.cur_camp.location.id;
          this.$router.push({name: 'camps.select'});
        }
      })
    },
    back() {
      this.enrol_data.id = this.cur_enrol_id;
      this.enrol_data.camp_id = this.selected_id;
      this.enrol_data.fee = this.cur_camp.camp.price;
      this.enrol_data.next_id = this.cur_enrol_id > 0 ? this.cur_enrol_id - 1 : 0;
      this.$store.dispatch('camps/setEnrol', {enrol: this.enrol_data}).then(response => {
        if (this.enrol_data.next_id == this.enrol_data.id) {
          this.$router.push({name: 'camps.all-children'});
        } else {
          this.selected_id = this.enrols[this.enrol_data.next_id].camp_id ? this.enrols[this.enrol_data.next_id].camp_id : this.selected_id;
          this.location_id = this.cur_camp.location.id;
          this.$router.push({name: 'camps.select'});
        }
      })
    },
    showWarning(message) {
      this.msg.type = 'warning';
      this.msg.message = message;
      this.msg.title = 'Warning!';
      this.$refs.simplert.openSimplert(this.msg);
    }
  }
}
</script>

<style>
  .card-wrapper {
    display: table;
  }
  .camp-list {
    justify-content: flex-start !important;
  }
</style>