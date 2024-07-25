<template>
 <div class="container mt-5 row justify-content-center">
        <div class="p-3 bg-light rounded shadow w-50">
            <div class="card-body">
                <h4 class="card-title text-center mb-2">Create Your Product</h4>
                <p class="text-center mb-4">Start posting your product to make money here!</p>
                <form action="#" method="post" enctype="multipart/form-data">
                    <div class="form-row d-flex" style="gap: 10px">
                        <div class="col mb-3">
                            <label for="productname" class="form-label">Product Name:</label>
                            <input type="text" class="form-control" id="productname" name="productname" placeholder="Enter your product name" required>
                        </div>

                        <div class="col mb-3">
                            <label for="price" class="form-label">Price:</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Enter your price" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter the quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter product description" required></textarea>
                    </div>

                    <label for="image" class="form-label">Image:</label>
                    <div class="mb-3 d-flex" style="gap: 10px">
                        <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                        <div class="col">
                            <button type="submit" class="btn btn-dark btn-lg" style="width: 120px;">Post</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>