<template>
  <div v-if="load_complete">
    <top-space/>
    <top-space>
      <div slot="title">Camps for {{ cur_enrol.id >= 0 ? cur_enrol.child_name : '' }}</div>
      <div slot="sub-title">What camps do you want to register for?</div>
    </top-space>

    <section class="mbr-section article content1 cid-qRjNQCoqtg" id="content1-57">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-5">
            <p><strong>Select a Location:</strong></p>
            <p>
              <select ref="select_location" class="form-control" :value="this.location_id ? this.location_id : (this.cur_camp ? this.cur_camp.location_id : 0)" required="" @change="selectLocation">
                <option value="0" disabled>--- Please Select ---</option>
                <option v-for="(items, key) in grouped_camps" :key="key" :value="items[0].location_id">{{ key }}</option>
              </select>
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="features3 cid-qQWAzjWbWN">
      <div class="container">
        <div class="media-container-row camp-list">
          <div v-for="camp in loc_camps" :key="camp.id" class="card p-3 col-12 col-md-6 col-lg-4">
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
                <span v-if="camp_registered[camp.id]" class="btn btn-danger display-4 btn-radius span-btn">
                  Already Registered
                </span>
                <a v-else-if="selected_id ? selected_id == camp.id : cur_camp.id == camp.id" href="#" class="btn btn-secondary display-4" @click="selectCamp($event, camp.id)">
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
              <a v-if="selected_id" class="btn btn-primary display-4" href="#" @click="next">
                <span class="mbrib-rocket mbr-iconfont mbr-iconfont-btn"></span>
                NEXT
              </a>
              <a v-if="!selected_id" class="btn btn-danger display-4" href="#" @click="skip">
                <span class="mbrib-rocket mbr-iconfont mbr-iconfont-btn"></span>
                No camps for this child
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
import _ from 'lodash'

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
        type: 'error',
        useConfirmBtn: false,
        onConfirm: null
      },
      camp_registered: [],
      load_complete: false
    }
  },
  computed: {
    ...mapGetters({
      post: 'camps/post',
      camps: 'camps/camps',
      enrols: 'camps/enrols',
      children: 'camps/children'
    }),
    grouped_camps() {
      return _.groupBy(this.camps, camp => camp.location);
    },
    cur_camp() {
      let camp_id = this.selected_id ? this.selected_id : this.cur_enrol.camp_id;
      let camp = this.camps.find(camp => camp.id == camp_id);
      return camp ? camp : this.camps[0];
    },
    loc_camps() {
      let loc_id = this.location_id ? this.location_id : this.cur_camp.location_id;
      return this.camps.filter(camp => camp.location_id == loc_id);
    },
    cur_enrol() {
      return this.enrols.find(enrol => enrol.selecting === true);
    }
  },
  created() {
    if (!this.enrols.length || !this.children.length) {
      this.$router.push({name: 'camps.search'});
      return ;
    }
    this.selected_id = this.cur_enrol.camp_id;
    this.$store.dispatch('camps/fetchLocationCamps', {post_id: this.post.id}).then(() => {
      this.checkRegistered(this.cur_enrol.child_id);
    })
  },
  methods: {
    selectLocation(e) {
      this.location_id = e.target.value;
    },
    selectCamp(e, id) {
      e.preventDefault();
      this.selected_id = id;
    },
    checkRegistered(child_id) {
      this.camps.filter((camp, index) => {
        axios.post('/api/check-registered', {
          camp_id: camp.id,
          child_id: child_id
        }).then(response => {
          this.camp_registered[camp.id] = response.data.status;
          if (this.selected_id == camp.id && this.camp_registered[camp.id]) {
            this.selected_id = 0
          }
          if (index == this.camps.length - 1) {
            this.load_complete = true
          }
        });
        return true;
      })
    },
    setEnrolData() {
      this.enrol_data.id = this.cur_enrol.id;
      this.enrol_data.camp_id = this.selected_id;
      this.enrol_data.fee = this.selected_id ? this.cur_camp.price : 0;
      this.enrol_data.camp_name = this.selected_id ? this.cur_camp.topic : '';
    },
    nextSelect() {
      this.load_complete = false;
      this.selected_id = this.enrols[this.enrol_data.next_id].camp_id ? this.enrols[this.enrol_data.next_id].camp_id : this.selected_id;
      this.location_id = this.cur_camp.location_id;
      this.checkRegistered(this.enrols[this.enrol_data.next_id].child_id);
      this.$router.push({name: 'camps.select'});
    },
    next() {
      this.setEnrolData();
      this.enrol_data.next_id = this.cur_enrol.id < this.enrols.length - 1 ? this.cur_enrol.id + 1 : this.cur_enrol.id;
      this.$store.dispatch('camps/setEnrol', {enrol: this.enrol_data}).then(response => {
        if (this.enrol_data.next_id == this.enrol_data.id) {
          this.$router.push({name: 'camps.payment'});
        } else {
          this.nextSelect();
        }
      })
    },
    back() {
      this.setEnrolData();
      this.enrol_data.next_id = this.cur_enrol.id > 0 ? this.cur_enrol.id - 1 : 0;
      this.$store.dispatch('camps/setEnrol', {enrol: this.enrol_data}).then(response => {
        if (this.enrol_data.next_id == this.enrol_data.id) {
          this.$router.push({name: 'camps.all-children'});
        } else {
          this.nextSelect();
        }
      })
    },
    skip() {
      this.msg.useConfirmBtn = true;
      this.msg.message = 'Are you sure?';
      this.msg.onConfirm = this.next
      this.$refs.simplert.openSimplert(this.msg);
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
  .span-btn {
    margin-top: 1rem !important;
    margin-bottom: 0 !important;
  }
</style>