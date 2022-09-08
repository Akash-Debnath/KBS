<template>
    <div v-if="isHistoryCheck">
        <v-app v-if="isLoading">
            <p class="text-small">Total: {{total_history}}</p>
            <v-main>
                <v-container class="p-0 position-relative overflow-hidden">
                    <v-row justify="end">
                        <v-col md="3" class="customer-search-wrapper">
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col class="customer-data-table-wrapper">
                            <v-data-table :headers="headers" :items="history_list" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
                                <template v-slot:item.user.first_name="{item}">
                                    {{ item.user ? (item.user.first_name +' '+ item.user.last_name) : '' }}
                                </template>
                            </v-data-table>

                        </v-col>
                    </v-row>
                    <v-row justify="end" class="pagination-wrapper">
                        <v-col>
                            <v-pagination :total-visible="7" v-model="pagination.current" :length="pagination.total" @input="onPageChange">
                            </v-pagination>
                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
        </v-app>

        <Loading v-else ></Loading>
    </div>
</template>

<script>
    import axios from 'axios'
    import Loading from "@/components/loader/loading";
    export default {
        name: "HistoyList",

        components:{
            Loading
        },

        props:['isHistoryCheck','postID'],

        data(){
          return {
              isLoading           : false,
              search              :"",
              pagination  :{
                  current         :1,
                  per_page        : 20,
                  total           : ''
              },
              total_history : '',
              headers: [
                  {
                      text: 'Last Edited',
                      value: 'updated_at',
                  },
                  {
                      text: 'Full Name',
                      value: 'user.first_name',
                  },
                  {
                      text: 'Operation',
                      value: 'operation_type',
                  },

              ],
              history_list : ''
          }
        },

        methods:{
            getPostHistory(){
                let _that = this;

                axios.get('history/'+_that.postID,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                        console.log(response.data)

                       _that.total_history = response.data.history_list.total

                    _that.history_list = response.data.history_list
                    _that.pagination.current = response.data.history_list.current_page;
                    _that.pagination.total = response.data.history_list.last_page;
                    _that.history_list      = response.data.history_list.data;
                    console.log(_that.history_list)
                    _that.isLoading = true;
                });
            },
            onPageChange() {
                this.getPostHistory();
            },
        },

        created() {
            // console.log(this.$route.params)
            this.getPostHistory();
        }
    }
</script>

<style scoped>

</style>