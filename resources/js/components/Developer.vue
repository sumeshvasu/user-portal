<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Developer List</div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th></th>
                <th>Avatar</th>
                <th>Email</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Phone</th>
                <th>Address</th>
                <th colspan="2" class="th-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="developer in developers.data"
                :key="developer.id"
                class="user-panel"
              >
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <!-- /.card-body -->
          <div class="card-footer">
            <pagination
              :data="developers"
              @pagination-change-page="getResults"
              :limit="3"
            ></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log('Developer Component Loaded!!!')
  },
  created: function () {
    this.loadDevelopers()
  },
  data() {
    /**
     * Initialise variables and form
     */
    return {
      headerInfo: {
        'Content-Type': 'application/json',
      },
      editmode: false,
      developers: {},
      file: null,
      form: new Form({
        id: '',
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        address: '',
        avatar: '',
      }),
    }
  },
  methods: {
    /**
     * Load developers
     */
    loadDevelopers() {
      const localVar = this

      axios({
        url: 'api/developer',
        method: 'get',
      }).then(function (response) {
        localVar.developers = response
      })
    },
    /**
     * Pagination
     */
    getResults(page = 1) {
      axios.get('api/developer?page=' + page).then((response) => {
        this.developers = response.data
      })
    },
  },
}
</script>
