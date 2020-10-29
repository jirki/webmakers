<template>
  <div>
    <div class="loading" v-if="showLoader">Loading&#8230;</div>

    <gmap-map
      :center="center"
      :zoom="12"
      :clickable="true"
      :draggable="true"

      @click="getWeather"
    >
    </gmap-map>

    <div id="myModal" class="modal" v-if="showModal">
      <div class="modal-content">
        <span class="close" v-on:click="closeModal">&times;</span>
        <h2>{{ result.name }}</h2>
        <div>
            <p>Temp: {{ result.temp }}°C</p>
            <p>Wind: {{ result.wind }}m/s</p>
            <p>Clouds: {{ result.clouds }}%</p>
            <p>Description: {{ result.description }}</p>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "GoogleMap",
  data() {
    return {
      center: { 
        lat: 53.1214, 
        lng: 18.0143 
      },
      showLoader: false,
      showModal: false,
      result: {}
    };
  },

  mounted() {
    this.geolocate();
  },

  methods: {
    geolocate() {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
      });
    },
    closeModal() {
      this.showModal = false;
    },
    getWeather(event) {
      this.showLoader = true;
      var lat = event.latLng.lat()
      var lng = event.latLng.lng()

      
      axios.post('/api/weather/lat/' + lat + '/lng/' + lng)
            .then((response) => {
              this.showLoader = false;
              this.showModal  = true;
              var data        = response.data;
              this.result     = {
                name: data.name,
                description: data.description,
                temp: data.temp,
                wind: data.wind,
                clouds: data.clouds
              }
            })
            .catch(function (error) {
              console.log(error);
            });
    }
  }
};
</script>