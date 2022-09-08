<template>
  <div class="form-wrapper">
    <h2 class="section-title text-uppercase mb-20">Search</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="customerID">Customer ID</label>
          <input class="form-control" type="text" name="customerID" id="customerID">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="closedReasonCode">Closed Reason Code</label>
          <input class="form-control" type="text" name="closedReasonCode" id="closedReasonCode">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="skill">Skill</label>
          <input class="form-control" type="text" name="skill" id="skill">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="openTime">Open Time</label>
          <input class="form-control time" type="text" name="openTime" id="openTime">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="inKPI">In KPI</label>
          <input class="form-control" type="text" name="inKPI" id="inKPI">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="contactStatus">Contact Status</label>
          <input class="form-control" type="text" name="contactStatus" id="contactStatus">
        </div>
      </div>
    </div>

    <div class="card mb-15">
      <div class="card-header p-2 font-16">Arrival Date\Time</div>
      <div class="px-10 pt-10">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="arrivalDateTimeFrom">From</label>
              <input class="form-control date-time" type="text" name="arrivalDateTimeFrom" id="arrivalDateTimeFrom">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="arrivalDateTimeTo">To</label>
              <input class="form-control date-time" type="text" name="arrivalDateTimeTo" id="arrivalDateTimeTo">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="Agent_ID">Agent</label>
          <select id="Agent_ID" name="Agent_ID" class="form-control">
            <option value="*">All</option>
            <option value="1001">Test user</option>
            <option value="1002">sanaul2</option>
            <option value="1003">OThree</option>
            <option value="1004">sup_sanaul</option>
            <option value="1005">Razu</option>
            <option value="1010">Chayan</option>
            <option value="1012">Sharif</option>
            <option value="1017">Sadekul</option>
            <option value="1018">Harun</option>
            <option value="1011">Mausum</option>
            <option value="1080">Nafees</option>
          </select>
        </div>
      </div>
    </div>

    <div class="card mb-15">
      <div class="card-header p-2 font-16">First Open Time</div>
      <div class="px-10 pt-10">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="firstOpenTimeFrom">From</label>
              <input class="form-control date-time" type="text" name="firstOpenTimeFrom" id="firstOpenTimeFrom">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="firstOpenTimeTo">To</label>
              <input class="form-control date-time" type="text" name="firstOpenTimeTo" id="firstOpenTimeTo">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-15">
      <div class="card-header p-2 font-16">First Open Time</div>
      <div class="px-10 pt-10">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="firstOpenTimeFrom">From</label>
              <input class="form-control date-time" type="text" name="firstOpenTimeFrom" id="firstOpenTimeFrom">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-15">
              <label for="firstOpenTimeTo">To</label>
              <input class="form-control date-time" type="text" name="firstOpenTimeTo" id="firstOpenTimeTo">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group mb-15">
          <label for="emailSubject">Email Subject</label>
          <input class="form-control" type="text" name="emailSubject" id="emailSubject">
        </div>
      </div>
    </div>

    <div class="form-group text-right">
      <button class="btn common-gradient-btn ripple-btn px-50"><i class="fas fa-search"></i> <span class="ml-1">Search</span></button>
    </div>
  </div>
</template>

<script>
export default {
name: "search"
}
</script>

<style scoped>

</style>