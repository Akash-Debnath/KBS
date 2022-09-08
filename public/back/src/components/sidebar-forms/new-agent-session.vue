<template>
  <div class="form-wrapper">
    <h2 class="section-title text-uppercase mb-20">Add New Agent Session</h2>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="agentID">AgentID <span class="required">*</span></label>
          <input class="form-control" type="text" name="agentID" id="agentID" required>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="shift_code">Shift <span class="required">*</span></label>
          <select id="shift_code" name="shift_code" class="form-control">
            <option value="">--Select--</option>
            <option value="EMRN" data-start-time="07:00">Early Morning</option>
            <option value="MORN" data-start-time="09:00">Morning</option>
            <option value="LMRN" data-start-time="10:00">Late Morning</option>
            <option value="AFTN" data-start-time="12:00">Afternoon</option>
            <option value="EVEN" data-start-time="15:00">Evening</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="shiftType">Shift Type <span class="required">*</span></label>
          <select name="is_regular_shift" id="shiftType" class="form-control">
            <option selected="selected" value="Y">Regular</option>
            <option value="N">Irregular</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="agentSessionDate">Date</label>
          <input class="form-control date" type="text" name="agentSessionDate" id="agentSessionDate">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="startTime">Start Time</label>
          <input class="form-control time" type="text" name="startTime" id="startTime">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group mb-15">
          <label>Message</label>
          <div id="summernote"></div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group mb-15">
          <label  class="d-block">Media</label>
          <input type="file" id="mediaFile" multiple="multiple" />
          <ul id="output_files"></ul>
        </div>
      </div>
    </div>

    <div class="form-group text-right">
      <button class="btn common-gradient-btn ripple-btn px-50">Add</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "new-agent-session"
}
</script>

<style scoped>

</style>