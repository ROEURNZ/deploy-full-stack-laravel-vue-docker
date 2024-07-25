<template>
    <div class="container">
        <button type="button" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">New Category</button>
        
        <!-- Categories List -->
        <div class="list-cate" style="width: 100%;">
            <table class="table table-white table-striped">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Image</th>
                        <th scope="col" style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, index) in categories" :key="category.id">
                        <th scope="row">{{ index + 1 }}</th>
                        <td @mouseenter="showProducts(category.id)" @mouseleave="hideProducts()" @click="getProductCategory(category.id)" style="cursor: pointer;">
                            {{ category.category_name }}
                        </td>
                        <td>
                            <img :src="category.image" alt="Category Image" style="width: 50px; height: auto;">
                        </td>
                        <td class="gap-2 d-flex">
                            <button class="btn btn-primary" @click="editCategory(category)">Edit</button>
                            <button class="btn btn-danger" @click="deleteCategories(category.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- New Category Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ isEdit ? 'Edit Category' : 'New Category' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="isEdit ? updateCate() : createCate()">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Name your category:</label>
                                <input type="text" class="form-control" id="recipient-name" v-model="category_name">
                            </div>
                            <div>
                                <label for="pro-image" class="col-form-label">Photo:</label>
                                <input type="file" class="form-control" id="pro-image" @change="handleImageUpload">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="isEdit ? updateCate() : createCate()">{{ isEdit ? 'Update' : 'Create' }}</button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</template>

<script>
import api from "@/views/api"; 
import cards_product from '@/Components/Card/CardComponent.vue'; 
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js' 

export default {
    components: {
        cards_product
    },
    data() {
        return {
            categories: [],
            hoveredProducts: null,
            selectedCategoryProducts: null,
            category_name: '',
            cate_img: null, // Variable to hold the uploaded image file
            isEdit: false, // Flag to determine if editing mode is enabled
            currentCategory: null // Currently selected category for editing
        };
    },
    mounted() {
        this.fetchProductCategories();
    },
    methods: {
        async fetchProductCategories() {
            try {
                const response = await api.listCategories();
                if (response.status === 200) {
                    this.categories = response.data.data;
                } else {
                    console.error("Error fetching categories: ", response);
                }
            } catch (error) {
                console.error("API error: ", error);
            }
        },
        async showProducts(categoryId) {
            try {
                const response = await api.productCategory(categoryId);
                if (response.status === 200) {
                    this.hoveredProducts = response.data.data;
                } else {
                    console.error("Error fetching products: ", response);
                }
            } catch (error) {
                console.error("API error: ", error);
            }
        },
        hideProducts() {
            this.hoveredProducts = null;
        },
        async getProductCategory(categoryId) {
            try {
                const response = await api.productCategory(categoryId);
                if (response.status === 200) {
                    this.selectedCategoryProducts = response.data.data;
                    if (!this.selectedCategoryProducts || this.selectedCategoryProducts.length === 0) {
                        alert("No products found for this category!");
                    }
                } else {
                    console.error("Error fetching products: ", response);
                }
            } catch (error) {
                console.error("API error: ", error);
            }
        },
        handleImageUpload(event) {
            this.cate_img = event.target.files[0];
            console.log("Uploaded image:", this.cate_img);
        },
        async createCate() {
            const formData = new FormData();
            formData.append('category_name', this.category_name);
            if (this.cate_img) {
                formData.append('image', this.cate_img);
            }

            try {
                const response = await api.createCategory(formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response.status === 201) {
                    this.fetchProductCategories();
                    alert("Category created successfully!");
                    window.location.reload();
                } 
                
            } catch (error) {
                console.error("API error: ", error);
                alert("Failed to create category. Please try again.");
            }

            // Reset the form fields after submission
            this.category_name = '';
            this.cate_img = null;
        },


        editCategory(category) {
            this.isEdit = true;
            this.currentCategory = category;
            this.category_name = category.category_name;
            // Trigger the modal to open
            const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            modal.show();
        },
        async updateCate() {
            const formData = new FormData();
            formData.append('category_name', this.category_name);
            if (this.cate_img) {
                formData.append('image', this.cate_img);
                console.log("Updated image:", this.cate_img);
            } else {
                formData.append('image', this.currentCategory.image);
            }

            try {
                const response = await api.updateCategory(this.currentCategory.id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response.status === 200) {
                    this.fetchProductCategories();
                    alert("Category updated successfully!");
                    window.location.reload();
                } else {
                    console.error("Error updating category: ", response);
                    alert("Failed to update category. Please try again.");
                }
            } catch (error) {
                console.error("API error: ", error);
                alert("Failed to update category. Please try again.");
            }

            // Reset the form fields after submission
            this.category_name = '';
            this.cate_img = null;
            this.isEdit = false;
            this.currentCategory = null;
        },

        async deleteCategories(cateId) {
            try {
                const response = await api.deleteCategory(cateId);
                if (response.status === 200) {
                    this.fetchProductCategories();
                    alert("Category deleted successfully!");
                } else {
                    console.error("Error deleting category: ", response);
                    alert("Failed to delete category. Please try again.");
                }
            } catch (error) {
                console.error("API error: ", error);
                alert("Failed to delete category. Please try again.");
            }
        }
    }
};
</script>

<style>
/* Add your custom styles here */
</style>
