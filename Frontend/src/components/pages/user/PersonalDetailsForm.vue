<template>
  <form class="personal-details-form">
    <div class="form-row">
      <div class="form-field">
        <label>First Name</label>
        <input type="text" v-model="firstName" class="input-field" :readonly="!isEditing" />
      </div>
      <div class="form-field">
        <label>Last Name</label>
        <input type="text" v-model="lastName" class="input-field" :readonly="!isEditing" />
      </div>
    </div>

    <div class="form-row">
      <div class="form-field">
        <label>Email Address</label>
        <input type="email" v-model="emailAddress" class="input-field" readonly />
      </div>
      <div class="form-field">
        <label>Phone No</label>
        <input type="text" v-model="telNo" class="input-field" :readonly="!isEditing" />
      </div>
    </div>

    <div class="form-row">
      <div class="form-field">
        <label>Shipping Address</label>
        <input type="text" v-model="shippingAddress" class="input-field" :readonly="!isEditing" />
      </div>
      <div class="form-field">
        <label>Billing Address</label>
        <input type="text" v-model="billingAddress" class="input-field" :readonly="!isEditing" />
      </div>
    </div>
  </form>
</template>

<script>
export default {
  name: "PersonalDetailsForm",
  props: {
    isEditing: Boolean,
  },
  data() {
    return {
      firstName: "",
      lastName: "",
      emailAddress: "",
      telNo: "",
      shippingAddress: "",
      billingAddress: "",
    };
  },
  mounted() {
    this.firstName = this.sanitizeValue(localStorage.getItem("user_first_name"));
    this.lastName = this.sanitizeValue(localStorage.getItem("user_last_name"));
    this.emailAddress = this.sanitizeValue(localStorage.getItem("user_email"));
    this.telNo = this.sanitizeValue(localStorage.getItem("tel_no"));
    this.shippingAddress = this.sanitizeValue(localStorage.getItem("shipping_address"));
    this.billingAddress = this.sanitizeValue(localStorage.getItem("billing_address"));
  },
  methods: {
    sanitizeValue(value) {
      return value === "null" || value === null || value === undefined ? "" : value;
    },
    getFormData() {
      return {
        first_name: this.firstName || "",
        surname: this.lastName || "",
        email_address: this.emailAddress || "",
        tel_no: this.telNo || "",
        shipping_address: this.shippingAddress || "",
        billing_address: this.billingAddress || "",
      };
    }
  }

};
</script>

<style scoped>
.personal-details-form {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-row {
  display: flex;
  align-items: stretch;
  gap: 32px;
  flex-wrap: wrap;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
}

.input-field {
  border-radius: 8px;
  background-color: rgba(249, 249, 249, 1);
  padding: 14px 20px;
  font-size: 16px;
  font-weight: 400;
  border: none;
  width: 100%;
}

label {
  font-size: 16px;
  font-weight: 400;
}

@media (max-width: 991px) {
  .form-field {
    width: 100%;
  }
}
</style>
