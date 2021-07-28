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
                <td class="align-middle">
                  {{ developer }}
                  <!-- <img
                        v-bind:src="'/image/avatar/' + developer.avatar"
                        class="img-circle elevation-2"
                      /> -->
                </td>
                <td class="align-middle">
                  {{ developer.email }}
                </td>
                <td class="align-middle">
                  <!-- {{ developer.firstname }} -->
                </td>
                <td class="align-middle">
                  <!-- {{ developer.lastname }} -->
                </td>
                <td class="align-middle">
                  <!-- {{ developer.phone }} -->
                </td>
                <td class="align-middle">
                  <!-- {{ developer.address }} -->
                </td>
                <td align="center" class="align-middle">
                  <!-- <i
                      @click="editDeveloperModal(developer)"
                      class="fa fa-pen-fancy blue action-switch"
                    ></i> -->
                </td>
                <td align="center" class="align-middle">
                  <!-- <i
                      @click="deleteDeveloper(developer.id)"
                      class="fa fa-trash-alt red action-switch"
                    ></i> -->
                </td>
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
    this.loadDevelopers()

    console.log('Developer Component Loaded!!!')
  },
  data() {
    /**
     * Initialise variables and form
     */
    return {
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
      loader_image: false,
    }
  },
  methods: {
    /**
     * Load developers
     */
    loadDevelopers() {
      axios.get('api/developer').then(({ data }) => {
        this.developers = data
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
