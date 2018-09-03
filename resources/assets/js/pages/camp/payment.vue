<template>
  <div v-if="camps">
    <top-space>
      <div slot="title"><br><br>Almost There...</div>
    </top-space>

    <section class="mbr-section article content1 cid-qZDljz1dnu" id="content1-a5">
      <div class="mbr-text col-12 col-md-12 mbr-fonts-style display-7">
        <h2>TOTAL: $198</h2>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDmx10m9q" id="content8-a7">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-secondary display-5" href="#">
                <span class="mbri-credit-card mbr-iconfont mbr-iconfont-btn"></span>
                Pay Now
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section article content1 cid-qZDljz1dnu" id="content1-a5">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-4 mbr-fonts-style display-7">
            <p>Gift Card/Voucher Code:</p>
            <input type="text" class="form-control" name="name" data-form-field="Name" id="name-form1-9a">
            <button href="" type="submit" class="btn btn-primary btn-form display-4">Apply</button>
            <p><br></p>
            <p><br></p>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section article content1 cid-qZDmUrxXz0" id="content1-a8">
      <div class="container">
        <div class="media-container-row">
          <div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
            <h3>Camp Breakdown</h3>
            <table align="center">
              <tr>
                <th>Name</th>
                <th>Camp</th>
                <th>Price</th>
              </tr>
              <tr>
                <td>Joshua Brooks</td>
                <td>Game Making Mania - The Ponds High School</td>
                <td>$99</td>
              </tr>
              <tr>
                <td></td>
                <td>Minecraft Modding Mayhem - The Ponds High School</td>
                <td>$99</td>
              </tr>
              <tr>
                <td>Lachlan Brooks</td>
                <td>Minecraft Modding Mayhem - The Ponds High School</td>
                <td>$99</td>
              </tr>
            </table>
            <p><br></p>
            <p><br></p>
            <p><br></p>
            <p><br></p>
          </div>
        </div>
      </div>
    </section>

    <section class="mbr-section content8 cid-qZDmkvSJs3" id="content8-a6">
      <div class="container">
        <div class="media-container-row title">
          <div class="col-12 col-md-8">
            <div class="mbr-section-btn align-center">
              <a class="btn btn-black-outline display-4" href="#" @click="back">
                <span class="mbri-arrow-prev mbr-iconfont mbr-iconfont-btn"></span>
                Back
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <bottom-space/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios';

export default {
  data() {
    return {
      location_id: 0,
      selected_id: this.camp_id,
    }
  },
  computed: {
    ...mapGetters({
      isLoggedin: 'auth/check',
      camps: 'camp/camps',
      camp_id: 'camp/camp_id',
      post: 'camp/post'
    }),
    cur_camp() {
      var camp_id = -1, loc_id = -1;
      var filter_arr = this.camps.map((item, index) => {
        let camp = item.camps.map((camp, index) => {
          if (camp.id == this.camp_id){
            camp_id = index;
          }
          return item;
        })
        if (camp_id >= 0) {
          loc_id = index;
        }
        return item;
      });

      if (camp_id >= 0 && loc_id >= 0) {
        return this.camps[loc_id].camps[camp_id];
      }
      return null;
    },
    cur_loc() {
      var camp_id = -1, loc_id = -1;
      var filter_arr = this.camps.map((item, index) => {
        let camp = item.camps.map((camp, index) => {
          if (camp.id == this.camp_id){
            camp_id = index;
          }
          return item;
        })
        if (camp_id >= 0) {
          loc_id = index;
        }
        return item;
      });

      if (camp_id >= 0 && loc_id >= 0) {
        return this.camps[loc_id].location;
      }
      return null;
    },
    location_camps() {
      let loc_id = this.location_id ? this.location_id : this.cur_loc.id;
      let filter_arr = this.camps.filter(item => {
        return item.location.id == loc_id;
      });
      if (filter_arr.length > 0) {
        return filter_arr[0].camps;
      }
      return [];
    }
  },
  created() {
    this.$store.dispatch('camp/fetchLocationCamps', {post_id: this.post.id})
  },
  methods: {
    changeLocation(e) {
      this.location_id = e.target.value;
    },
    selectCamp(e, id) {
      e.preventDefault();
      this.selected_id = id;
    },
    back() {
      this.$router.push({name: 'camp.select'});
    }
  }
}
</script>

<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    color: #767676;
    text-align: center;
  }
  th, td {
    padding: 7px;
  }
</style>