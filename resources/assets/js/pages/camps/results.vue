<template>
  <div>
    <section class="header11 cid-qQWMDyug1S mbr-fullscreen" id="header11-s">
      <div class="container align-left">
        <div class="media-container-column mbr-white col-md-12">
          <h3 class="mbr-section-subtitle py-3 mbr-fonts-style display-5">&nbsp;</h3>
          <h1 class="mbr-section-title py-3 mbr-fonts-style display-2">
            We've found the closest&nbsp;<strong>CodeSpace Camps</strong>&nbsp;to {{ post.suburb }} {{ post.state }}.
          </h1>
          <p class="mbr-text py-3 mbr-fonts-style display-5">
            CodeSpace Education runs a range of School Holiday CodeSpace Camps all over Australia.<br><br>We have curated the closest workshops for you below.
          </p>
        </div>
      </div>
      <div class="mbr-arrow hidden-sm-down" aria-hidden="true">
        <a href="#next">
          <i class="mbri-down mbr-iconfont"></i>
        </a>
      </div>
    </section>

    <div v-for="(loc_camps, key) in grouped_camps" :key="key">
      <section id="next" class="mbr-section info1 cid-qQWMc4sypE">
        <div class="container">
          <div class="row justify-content-center content-row">
            <div class="media-container-column title col-12 col-lg-7 col-md-6">
              <h3 class="mbr-section-subtitle align-left mbr-light pb-3 mbr-fonts-style display-5">
                Only {{ loc_camps[0].dist }}km from {{ post.suburb }} {{ post.state }}
              </h3>
              <h2 class="align-left mbr-bold mbr-fonts-style display-2">CAMPS @ {{ key }}</h2>
            </div>
            <div class="media-container-column col-12 col-lg-3 col-md-4">
            </div>
          </div>
        </div>
      </section>

      <section class="features3 cid-qQWAzjWbWN">
        <div class="container">
          <div v-for="row in (Math.floor((loc_camps.length-1)/3) + 1)" :key="row" class="media-container-row camp-list">
            <div v-for="id in loc_camps.length - (row - 1) * 3" :key="id" class="card p-3 col-12 col-md-6 col-lg-4">
              <div class="card-wrapper">
                <div class="card-img">
                  <img src="/assets/images/file-27-624x465.png">
                </div>
                <div class="card-box">
                  <h4 class="card-title mbr-fonts-style display-7">{{ loc_camps[(row-1) * 3 + id - 1].topic }}</h4>
                  <p class="mbr-text mbr-fonts-style display-7">{{ loc_camps[(row-1) * 3 + id - 1].topicDesc }}<br><br></p>
                  <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-calendar">&nbsp;</span> {{ loc_camps[(row-1) * 3 + id - 1].start_date }} ({{ loc_camps[(row-1) * 3 + id - 1].length }})</p>
                  <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-clock">&nbsp;</span> {{ loc_camps[(row-1) * 3 + id - 1].startTime }} - {{ loc_camps[(row-1) * 3 + id - 1].endTime }} with drop off from {{ loc_camps[(row-1) * 3 + id - 1].arriveTime }} and pickup until {{ loc_camps[(row-1) * 3 + id - 1].departTime }}</p>
                  <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-pin">&nbsp;</span>{{ loc_camps[(row-1) * 3 + id - 1].name }}</p>
                  <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-user">&nbsp;</span> Ages {{ loc_camps[(row-1) * 3 + id - 1].ages }}</p>
                  <p class="mbr-section-text  align-center mbr-fonts-style display-7"><span class="mbri-sale">&nbsp;</span> ${{ loc_camps[(row-1) * 3 + id - 1].price }}</p>
                </div>
                <div class="mbr-section-btn text-center">
                  <a href="#" class="btn btn-primary display-4" @click="detail($event, loc_camps[(row-1) * 3 + id - 1].id)">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="mbrib-star mbr-iconfont mbr-iconfont-btn"></span>Learn More
                    &nbsp;&nbsp;&nbsp;&nbsp;
                  </a>
                </div>
                <div class="mbr-section-btn text-center">
                  <a v-if="loc_camps[(row-1) * 3 + id - 1].class_capacity - loc_camps[(row-1) * 3 + id - 1].sold > 0" href="#" class="btn btn-secondary display-4" @click="register($event, loc_camps[(row-1) * 3 + id - 1].id)">
                    &nbsp;&nbsp;
                    <span class="mbrib-rocket mbr-iconfont mbr-iconfont-btn"></span>Register Now
                    &nbsp;&nbsp;
                  </a>
                  <a v-else href="#" class="btn btn-danger display-4">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="mbri-close mbr-iconfont mbr-iconfont-btn"></span>
                    SOLD OUT
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  </a>
                </div>
                <br>
                <br>
                <div class="alert">
                  <strong>	&#9888; In High Demand!</strong>
                  This camp only has {{ loc_camps[(row-1) * 3 + id - 1].class_capacity - loc_camps[(row-1) * 3 + id - 1].sold > 9 ? 9 : loc_camps[(row-1) * 3 + id - 1].class_capacity - loc_camps[(row-1) * 3 + id - 1].sold }} spaces available.
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <bottom-space/>
    <!---End of One Location Section-->

    <section class="cid-qRjBWWzK0O mbr-fullscreen mbr-parallax-background" id="header15-4r">
      <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(7, 59, 76);"></div>
        <div class="container align-right">
          <div class="row">
            <div class="mbr-white col-lg-8 col-md-7 content-container">
                <h1 class="mbr-section-title mbr-bold pb-3 mbr-fonts-style display-1">That's it!</h1>
                <p class="mbr-text pb-3 mbr-fonts-style display-5">We don't have any more camps near you at this time.&nbsp;<br><br>We're releasing even more camps soon. Join our mailing list beside to be the first to know.&nbsp;</p>
            </div>
            <div class="col-lg-4 col-md-5">
              <div class="form-container">
                <div class="media-container-column" >
                  <form action="https://url.learncode.com.au/end-of-page-mailing-join/" method="get">
                    <div data-for="email">
                      <div class="form-group">
                        <input type="email" class="form-control px-3" name="email" placeholder="Email" required="">
                      </div>
                    </div>
                    <div data-for="phone">
                      <div class="form-group">
                        <input type="text" class="form-control px-3" name="postcode" placeholder="Postcode" required="" >
                      </div>
                    </div>
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-secondary btn-form display-4">Next</button>
                    </span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <simplert :useRadius="true" :useIcon="true" ref="simplert"></simplert>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';
import Form from 'vform'
import _ from 'lodash'
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
  computed: {
    ...mapGetters({
      post: 'camps/post',
      camps: 'camps/camps',
      isLoggedin: 'auth/check'
    }),
    grouped_camps() {
      return _.groupBy(this.camps, camp => camp.location);
    }
  },
  created() {
    this.$store.dispatch('camps/fetchLocationCamps', {post_id: this.post.id}).then(() => {
      // console.log(this.grouped_camps)
    })
  },
  methods: {
    detail(e, id) {
      e.preventDefault();
      this.$store.dispatch('camps/setCampId', {camp_id: id}).then(() => {
        this.$router.push({name: 'camps.details'})
      }).catch(error => {
        this.showError('Set Camp Id Error');
      })
    },
    register(e, id) {
      e.preventDefault();
      this.$store.dispatch('camps/setCampId', {camp_id: id}).then(() => {
        if (!this.isLoggedin) {
          this.$router.push({name: 'camps.register'});
        } else {
          this.$router.push({name: 'camps.child-details'});
        }
      }).catch(error => {
        this.showError('Set Camp Id Error');
      })
    },
    showError(message) {
      this.msg.title = 'Error';
      this.msg.message = message;
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

