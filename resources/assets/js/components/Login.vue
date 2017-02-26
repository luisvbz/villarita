<template>
    <div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form autocomplete="off" v-on:submit="signin">
      <div class="form-group has-feedback">
        <input type="text" id="email" class="form-control" placeholder="usuario" v-model="username" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" class="form-control" v-model="password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>

  </div>
  <!-- /.login-box-body -->
</div>
</template>
<script>
import auth from '../services/auth';

export default {
    data() {
            return {
                username: null,
                password: null,
                error: false
            }
        },
        methods: {
            signin(event) {
                event.preventDefault()
                auth.signin(this, this.username, this.password)
            }
        }
}
</script>