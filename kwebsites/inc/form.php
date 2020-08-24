<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>



<form id="kwf" action="<?php echo admin_url('admin-ajax.php') ?>" method="POST">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="kname">Your name <i v-if="subm" class="fas fa-check text-success"></i></label>
                <input v-model="kwname" type="text" class="form-control" id="kname" name="kname" required />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="kweb">Your URL <i v-if="subm" class="fas fa-check text-success"></i>
                <i v-else-if="err" class="fas fa-times text-danger"></i> </label>
                <input v-model="kwurl" type="text" class="form-control" id="kurl" name="kurl" required />
            </div>
        </div>
        </div> 
        <div class="text-center" v-if="kwurl && kwname">
        <button v-if="!subm && !err" type="button" class="btn btn-primary btn-lg" v-on:click="getURL">
		Check data
		</button>   
        </div>
            <div v-if="err">
                <p class="text-danger">There is an error in your data: {{err}}. Please correct it.</p>
                <p class="text-center"><button type="button" class="btn btn-primary btn-lg" v-on:click="restart">
                     Submit another data
                </button></p>
            </div>
            <div v-else-if="subm">
            <div class="row">
                <div class="col-md-6">
                <input type="hidden" name="action" value="kwebsitecp">
                <button type="submit" class="btn btn-primary btn-lg">
                    Submit this data
                </button>  
                </div>
                <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-lg" v-on:click="restart">
                     Submit another data
                </button> 
                </div>
            </div>
            </div>

</form>

<script>
new Vue({
  el: '#kwf',
  data: {
    kwname: '',
    kwurl: '',
    err: '',
    subm: ''
  },
  methods: {
    getURL: function() {
        var kurl = this.kwurl;
        axios.get(kurl).catch(err => {
            if (err.response) {
                    this.err = 'URL not found';
                }
            else {
                this.err = '';
                this.subm = 'true';        
                } }) },
    restart: function() {
        this.kwname = '';
        this.kwurl = '';
        this.subm = '';
        this.err = '';
    }
  }
})
  </script>