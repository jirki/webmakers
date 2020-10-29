<template>  
    <div class="container">
        <div class="loading" v-if="showLoader">Loading&#8230;</div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <button type="button" class="btn btn-primary">
                    Max Temp <span class="badge badge-light">{{ max }}</span>
                </button>
                <button type="button" class="btn btn-primary">
                    Min Temp <span class="badge badge-light">{{ min }}</span>
                </button>
                <button type="button" class="btn btn-primary">
                    Avg Temp <span class="badge badge-light">{{ avg }}</span>
                </button>
                <button type="button" class="btn btn-primary">
                    All Results <span class="badge badge-light">{{ allResults }}</span>
                </button>
                <button type="button" class="btn btn-primary">
                    Most popular city: <span class="badge badge-light">{{ popularCity }}</span>
                </button>
            </div>
            <div class="col-md-12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Temp</th>
                    <th scope="col">Clouds</th>
                    <th scope="col">Wind</th>
                    <th scope="col">Description</th>
                    <th scope="col">Distance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in dataShow" :key="item.id">
                      <td>{{ item.name }}</td>
                      <td>{{ item.temp }}°C</td>
                      <td>{{ item.clouds }}%</td>
                      <td>{{ item.wind }}m/s</td>
                      <td>{{ item.description }}</td>
                      <td>{{ distance(cords.lat, cords.lng, item.lat, item.lng) }}km</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p>
              <button @click="prevPage">Previous</button> 
              <button @click="nextPage">Next</button>
            </p>
        </div>        
    </div>
</template>

<script>
import axios from "axios";

export default {
  name: "History",
  data() {
    return {
        max: 0,
        min: 0,
        avg: 0,
        data: {},
        allResults: 0,
        popularCity: '',
        cords: {
          lat: 53.1231458,
          lng: 18.0068324
        },
        currentPage:1,
        pageSize:10,
        dataShow: {},
        showLoader: true
    };
  },
  mounted() {
    axios.get('/api/weather/all')
            .then((response) => {
              if (response.data.length > 0) {
                this.max          = response.data.max.max_temp;
                this.min          = response.data.min.min_temp;
                this.avg          = response.data.avg;
                this.data         = response.data.all;
                this.allResults   = response.data.count.count;
                this.popularCity  = response.data.popular.name;
                this.dataShow     = response.data.all.slice(this.currentPage, this.pageSize);
              }
              
              this.showLoader   = false;
            })
            .catch(function (error) {
              console.log(error);
            });
  },
  methods: {
    distance(lat1, lng1, lat2, lng2) {
      if ((lat1 == lat2) && (lng1 == lng2)) {
        return 0;
      }
      else {
        var radlat1   = Math.PI * lat1/180;
        var radlat2   = Math.PI * lat2/180;
        var theta     = lng1-lng2;
        var radtheta  = Math.PI * theta/180;
        var dist      = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);

        if (dist > 1) {
          dist = 1;
        }

        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        dist = dist * 1.609344;

        return dist.toFixed(2);
      }
    },
    nextPage() {
      if (Math.ceil(this.data.length/this.pageSize) > this.currentPage) {
        this.currentPage++;
        this.dataShow = [...this.data];
        this.dataShow = this.dataShow.splice((this.currentPage-1) * 10, this.pageSize);
      }
      
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.dataShow = [...this.data];
        this.dataShow = this.dataShow.splice((this.currentPage-1) * 10, this.pageSize);
      }
    }
  }
};
</script>