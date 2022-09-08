<template>
  <div class="form-wrapper">
    <h2 class="section-title text-uppercase mb-20">Delete</h2>

    <div class="row mt-50 mt-md-80">
      <div class="col-md-12">
        <figure class="mx-auto text-center">
          <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
        </figure>
        <p class="text-center">By clicking 'Delete' article completeley deleted form site, You canot recover back</p>

        <div class="form-group d-flex justify-content-center align-items-center">
          <button type="button" class="btn btn-danger rounded-pill px-30 mx-2"><i class="fas fa-trash"></i> Confirm</button>
          <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2"><i class="fas fa-times-circle"></i> Cancel</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
name: "delete-form.vue"
}
</script>

<style scoped>

</style>