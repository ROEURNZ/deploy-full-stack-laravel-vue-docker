<template>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class=" border-right" style="background-color: #222E3C;" id="sidebar-wrapper">
      <div class="py-4 sidebar-heading text-light">Admin Dashboard</div>
      <div class="list-group list-group-flush">
        <router-link to="/dashboard" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-house me-2"></i> Overview
        </router-link>
        <router-link to="/admin/orders" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-cart-dash me-2"></i> Orders
        </router-link>
        <div class="border-none btn-group">
          <router-link to="/products"
            class="list-group-item list-group-item-action text-light border-start-0 border-end-0 border-top-0 rounded-0"
            active-class="active" style="background-color: #222E3C;">
            <i class="bi bi-box me-2"></i> Products
          </router-link>
          <button type="button" class="m-2 border border-white opacity-25 btn-white dropdown-toggle dropdown-toggle-split rounded-0 d-flex justify-content-center align-items-center"
            active-class="active" data-bs-toggle="dropdown" aria-expanded="false" style="width:25px; height:25px; align-self:center;">
            <span class="visually-hidden" style="background-color: #FFFF;">Toggle Dropdown</span>
          </button>
          <ul class="opacity-75 dropdown-menu rounded-0 bg-0">
            <!-- <li><a class="dropdown-item" href="/category">Categories</a></li> -->
            <router-link to="/creategory"
              class="dropdown-item list-group-item list-group-item-action text-light" active-class="active" style="background-color: #222E3C;">
              <i class="bi bi-box me-2"></i> Categories
            </router-link>

          </ul>
        </div>
        <router-link to="/users" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-person-lines-fill me-2"></i> Customers
        </router-link>
        <router-link to="/usercreatestore" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-shop me-2"></i> Store
        </router-link>

        <router-link to="/userCharge" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-bar-chart me-2"></i> Reports Payment
        </router-link>
        <router-link to="/admin/settings" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-gear me-2"></i> Settings
        </router-link>
        <router-link to="/logout" class="list-group-item list-group-item-action text-light"
          active-class="active" style="background-color: #222E3C;">
          <i class="bi bi-box-arrow-right me-2"></i> Logout
        </router-link>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="shadow-sm navbar navbar-expand-lg navbar-custom border-bottom">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-collapse"
          aria-controls="nav-collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav-collapse">
          <ul class="ml-auto navbar-nav">
            <li class="mx-3 nav-item">
              <a class="nav-link notification-icon" href="#">
                <i class="bi bi-bell"></i>
                <span class="badge notification-badge">3</span>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="/" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <em>Admin</em>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Profile</a>
                <a class="dropdown-item" href="#">Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid scrollable-container">
        <router-view></router-view>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
</template>

<script>
export default {
  name: 'AdminLayout'
};
</script>

<style>
/* Global Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f8f9fa;
}

#wrapper {
  display: flex;
  width: 100%;
  height: 100vh;
}

/* Sidebar Styles */
#sidebar-wrapper {
  width: 250px;
}

.sidebar-heading {
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
}

.list-group-item {
  font-size: 1rem;
  position: relative;
  overflow: hidden;
}

.list-group-item-action:hover {
  background-color: #233244 !important;
}

/* Active Class with Background Blur Effect */
.list-group-item.active::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #495057;
  filter: blur(8px);
  z-index: -1;
}

.list-group-item.active {
  background-color: transparent !important;
  position: relative;
  z-index: 0;
}

/* Navbar Styles */
.navbar-custom {
  background-color: #ffffff !important;
  /* color: #ffffff; */
  display: flex;
  justify-content: space-between;
  align-items: end;
}

.navbar-custom .navbar-brand,
.navbar-custom .nav-link,
.navbar-custom .dropdown-item {
  color: #0f0e0e;
}

.navbar-custom .nav-link:hover,
.navbar-custom .dropdown-item:hover {
  color: #131310;
}

.notification-icon {
  font-size: 1.5rem;
  position: relative;
}

.notification-badge {
  background-color: #dc3545;
  border-radius: 50%;
  position: absolute;
  top: -5px;
  right: -10px;
  padding: 5px 10px;
  font-size: 0.75rem;
  color: white;
}

/* Content Styles */
#page-content-wrapper {
  flex: 1;
  overflow-y: auto;
  background: #F5F7FB;
}

.container-fluid {
  padding: 20px;
}
</style>
