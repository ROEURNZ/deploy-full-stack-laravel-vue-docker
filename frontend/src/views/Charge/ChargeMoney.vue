<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="step-indicator">
          <span class="step-number">1</span>
          <span class="step-separator"></span>
          <span class="step-number">2</span>
          <span class="step-separator"></span>
          <span class="step-number">3</span>
        </div>
        <h2 class="text-center my-4">Choose your plan</h2>
        <div class="row">
          <div class="col-md-4" v-for="(plan, index) in plans" :key="index">
            <div class="card plan-card h-100" :class="{ recommended: plan.recommended }">
              <div v-if="plan.recommended" class="card-header text-center">Recommended</div>
              <div class="card-body text-center">
                <h2 class="card-title" :class="{ 'text-success': plan.recommended }">
                  {{ plan.title }}
                </h2>
                <h1 class="card-subtitle mb-2">{{ plan.price }}</h1>
                <p class="card-text">{{ plan.description }}</p>
                <button @click="choosePlan(plan)" class="btn w-100" :class="plan.recommended ? 'btn-success' : 'btn-outline-primary'">
                    Get this plan
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '@/stores/user.js';

export default {
  data() {
    return {
      plans: [],
      selectedPlan: null,
      store_user: useUserStore(),
    };
  },
  async created() {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/lists');
      this.plans = response.data;
      console.log(this.plans);
    } catch (error) {
      console.error('Error fetching plans:', error);
    }
  },
  methods: {
    choosePlan(plan) {
      this.selectedPlan = plan;
      this.store_user.plan = plan; // Save selected plan to the store
      console.log(this.selectedPlan);
      console.log(this.store_user.plan);
      if (plan.title === 'Free') {
        // Add logic to handle 'Pro' plan selection
        alert('You cannot post the product Over 10 post')
        console.log(plan.title);
        this.$router.push('/plans');
      } else {
        // Handle other plans
        this.$router.push('/chargeMoney');
      }
    }
  }
};
</script>
<style scoped>
body {
  font-family: 'Arial', sans-serif;
}
.step-indicator {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.step-number {
  background-color: #fff;
  border: 2px solid #28a745;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.2em;
  color: #28a745;
}

.step-separator {
  width: 50px;
  height: 2px;
  background-color: #28a745;
}

.plan-card {
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 1rem;
}

.plan-card:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.recommended {
  border: 2px solid #28a745;
  position: relative;
}

.recommended .card-header {
  background-color: #28a745;
  color: white;
  font-size: 1rem;
  border-radius: 15px 15px 0 0;
  position: relative;
  top: -1.5rem;
  left: 50%;
  transform: translateX(-50%);
}

.card-title {
  font-size: 1.7rem;
  margin-top: 2.5rem;
}

.card-subtitle {
  font-size: 2rem;
  margin-bottom: 1rem;
  color: black;
}

.btn {
  border-radius: 25px;
  padding: 0.75rem 1rem;
}

.btn-outline-primary {
  border-color: #28a745;
  color: #28a745;
}

.btn-outline-primary:hover {
  background-color: #007bff;
  color: white;
}

.btn-success {
  background-color: #28a745;
  color: white;
  border: none;
}

.btn-success:hover {
  background-color: #218838;
}
</style>
