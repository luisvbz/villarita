<template>
<hsy-dialog class="confirm" v-model="visible">
  <div slot="title">Remove</div>
  <div slot="body">
    <div>This operation is irreversible, are you sure?</div>
    <div>
      <button @click="handleYes">Yes</button>
      <button>No</button>
    </div>
  </div>
</hsy-dialog>
</template>