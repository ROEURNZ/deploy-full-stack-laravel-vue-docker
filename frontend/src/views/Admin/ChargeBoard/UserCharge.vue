<template>
  <div>
    <!-- Sidebar -->
    <div class="d-flex">
      <div class="d-flex justify-content-between my-4">
        <div class="users card p-3 mx-2 bg-info" @click="showSection('all')">
          <h5 class="card-title">All Payment</h5>
          <p class="card-text">{{ payments.length }} / succeeded</p>
        </div>
        <div class="categories card p-3 mx-2 bg-danger" @click="showSection('failed')">
          <h5 class="card-title">Failed</h5>
          <p class="card-text"><strong>{{ failedCount }}</strong></p>
        </div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="mx-2">
      <table class="table table-dark table-striped">
        <thead>
          <tr class="text-center">
            <th scope="col">Id</th>
            <th scope="col">User</th>
            <th scope="col">Amount ($)</th>
            <th scope="col">Payment Method</th>
            <th scope="col">Email</th>
            <th scope="col">Date</th>
            <th scope="col" style="width: 20%;">Actions</th>
          </tr>
        </thead>
        <tbody >
          <tr v-for="(payment, index) in filteredPayments" :key="index" class="text-center ">
            <td scope="row">{{ reverseString((index + 1).toString()) }}</td>
            <td>{{ (payment.user.name)}}</td>
            <td>{{ (payment.amount )}}</td>
            <td>{{ payment.payment_method }}</td>
            <td>{{ payment.user.email }}</td>
            <td>{{ payment.user.next_charge_date}}</td>
            <td>
              <button @click="viewDetails(payment.id)" class="btn btn-info text-center btn-sm me-3">View</button>
              <button @click="DeleteFilled(payment.id)" class="btn btn-danger text-center btn-sm me-3">Delete</button>
            </td>
          </tr>
        </tbody>

      </table>
       <!-- Modal -->
       <div v-if="showModal" class="modal fade show d-block" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <p><strong>Payment ID:</strong> {{ paymentDetails.payment_intent_id }}</p>
              <!-- <p><strong>User:</strong> {{ paymentDetails.user.name }}</p> -->
              <p><strong>Amount:</strong> {{ paymentDetails.amount }}</p>
              <p><strong>Payment Method:</strong> {{ paymentDetails.payment_method }}</p>
              <p><strong>UserID:</strong> {{ paymentDetails.user_id}}</p>
              <p><strong>Date:</strong> {{ paymentDetails.created_at }}</p>.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminLayout',
  data() {
    return {
      payments: [],
      successCount: 0,
      refusedCount: 0,
      uncapturedCount: 0,
      failedCount: 0,
      currentSection: 'all',
      showModal: false,
      paymentDetails: {}
    };
  },
  mounted() {
    this.fetchPayments();
  },
  computed: {
    filteredPayments() {
      if (this.currentSection === 'all') {
        return this.payments;
      } else {
        return this.payments.filter(payment => payment.status === this.currentSection);
      }
    }
  },
  methods: {
    async fetchPayments() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/payment');
        this.payments = response.data.data;
        console.log(this.payments);
        this.updateCounts();
      } catch (error) {
        console.error('Error fetching payments: ', error);
      }
    },
    updateCounts() {
      this.failedCount = this.payments.filter(payment => payment.status === 'failed').length;
    },
    showSection(section) {
      this.currentSection = section;
    },
    closeModal() {
      this.showModal = false;
      this.paymentDetails = {};
    },
    reverseString(str) {
      return str.split('').reverse().join('');
    },
    async viewDetails(id) {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/show/payment/${id}`);
        this.paymentDetails = response.data.payment
        console.log(this.paymentDetails)
        this.showModal = true;
        console.log('Payment Details:', this.paymentDetails);
      } catch (error) {
        console.error('Error fetching payment details: ', error);
      }
    },
    async DeleteFilled(id) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/delete/payment/${id}`);
        // Remove the deleted payment from the local list
        this.payments = this.payments.filter(payment => payment.id !== id);
        this.updateCounts();
        console.log('Payment deleted:', id);
      } catch (error) {
        console.error('Error deleting payment: ', error);
      }
    }
  }
};
</script>

<style scoped>
.card {
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  padding: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.16);
  background-color: #f2f2f2;
  color: #333;
  width: 200px;
}
</style>
