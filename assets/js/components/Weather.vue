<template>
    <div class="col-md-12">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{ currentPosition.name }}</h5>
                <p class="card-text">
                <ul>
                    <li>Temp: {{ currentPosition.temp }}°C</li>
                    <li>Clouds: {{ currentPosition.clouds }}%</li>
                    <li>Wind: {{ currentPosition.wind }}m/s</li>
                    <li>Desc: {{ currentPosition.description }}</li>
                </ul>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
  name: "Weather",
  data() {
    return {
        currentPosition: {},
        cords: {
          lat: 53.1231458,
          lng: 18.0068324
        }
    };
  },
  mounted() {
    axios.get('/api/weather/lat/' + this.cords.lat + '/lng/' + this.cords.lng)
        .then((response) => {
          this.currentPosition = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
  },
};
</script>