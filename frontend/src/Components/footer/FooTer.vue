<template>
    <div class="footer mt-5 pt-4 pb-4">
      <div class="container">
        <footer class="row justify-content-between">
          <div class="col-md-3 mb-3">
            <div class="logo">
              <img src="../../assets/images/lOUKDO.png" alt="logo" class="img-fluid mb-2">
            </div>
            <p class="text-muted">Your one-stop shop for everything!</p>
          </div>
          <div class="col-md-2 mb-3">
            <h5>Products</h5>
            <ul class="list-unstyled">
              <li><a href="#" class="text-muted">New Arrivals</a></li>
              <li><a href="#" class="text-muted">Best Sellers</a></li>
              <li><a href="#" class="text-muted">Discounts</a></li>
            </ul>
          </div>
          <div class="col-md-2 mb-3">
            <h5>Support</h5>
            <ul class="list-unstyled">
              <li><a href="#" class="text-muted">FAQ</a></li>
              <li><a href="#" class="text-muted">Shipping & Returns</a></li>
              <li><a href="#" class="text-muted">Contact Us</a></li>
            </ul>
          </div>
          <div class="col-md-3 mb-3">
            <h5>Contact Us</h5>
            <p class="text-muted">123 Main St, Anytown, USA</p>
            <p class="text-muted">Email: info@example.com</p>
            <p class="text-muted">Phone: (123) 456-7890</p>
            <div>
              <a href="#"><i class="bi bi-facebook text-muted me-2"></i></a>
              <a href="#"><i class="bi bi-twitter text-muted me-2"></i></a>
              <a href="#"><i class="bi bi-instagram text-muted me-2"></i></a>
            </div>
          </div>
        </footer>
        <div class="text-center mt-3 text-muted">
          <p>&copy; 2024 Your Company. All rights reserved.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
  
  }
  </script>
  
  <style scoped>
  .footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
  }
  .footer h5 {
    font-weight: bold;
  }
  .footer a {
    text-decoration: none;
  }
  .footer a:hover {
    text-decoration: underline;
  }
  .footer img {
    max-width: 100px;
  }
  .footer p {
    margin-bottom: 0.5rem;
  }
  </style>
  