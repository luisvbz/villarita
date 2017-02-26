<template>
<section class="content-header">
      <h1>
        {{ title }}
        <small>{{ subtitle }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">{{ title }}</li>
      </ol>
    </section>
 </template>
 <script>
 export default {
 	props: ['title', 'subtitle','link']
 }
 </script>