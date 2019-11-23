<template>
  <div class="container-fluid">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Edit Product</div>
      <div class="body">
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Name</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input class="input" v-model="params.name">
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Slug</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input class="input" v-model="params.slug">
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Description</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <textarea class="input" v-model="params.description"></textarea>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Image Url</label>
          </div>
          <div class="field-body">
            <div class="field" style="height: 250px">
              <p class="control" style="height: 100%;">
                <drag-drop-file
                  v-model="files"
                  :str-validate="`required|size:10240`"
                  :is-show-error="true"
                  :is-image-overlay="showOverlay"
                  class="custom-drag-drop has-border"
                  :is-show-file-name="true"
                  :url-image="urlImage"
                  :btn-name="$t('create_campaign_page.upload_a_file')"
                  @base64-image="showImageBase64"
                  @removeImage="removeImage"
                  ref="files"
                >
                </drag-drop-file>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Rate</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input-only-number class="input" v-model="params.rate" :is-readonly="true"></input-only-number>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Rate Amount</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input-only-number class="input" v-model="params.rate_amount" :is-readonly="true"></input-only-number>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Price Base</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input-only-number class="input" v-model="params.price_base"></input-only-number>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Price</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input-only-number class="input" v-model="params.price"></input-only-number>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Special From</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input class="input" type="date" v-model="params.special_from">
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Special To</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input class="input" type="date" v-model="params.special_to">
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Amount In Stock</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control">
                <input-only-number class="input" v-model="params.amount_in_stock"></input-only-number>
              </p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Discount Id</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="select-groups-container field-item">
                <div class="select">
                  <dropdown style="width:100%">
                    <p class="active-selected capitalize" @click="resetErrors()" v-if="discount">
                      {{ `${_.get(discount, 'percent', '')} - ${_.get(discount, 'code', '')}` }}
                    </p>
                    <p class="active-selected" @click="resetErrors()" v-else>{{ $t('common.please_select') }}</p>
                    <dropdown-menu slot="dropdown" class="box-popup" style="height: 200px; overflow-y: auto;">
                      <dropdown-item
                        class="dropdown-item capitalize"
                        v-for="(item, index) in discounts"
                        :key="index"
                        @click.native="setDiscount(item)"
                        :title="_.get(item, 'code', '')"
                      >
                        {{ `${_.get(item, 'percent', '')} - ${_.get(item, 'code', '')}` }}
                      </dropdown-item>
                      <infinite-loading force-use-infinite-wrapper=".box-popup" @infinite="loadMoreDiscount"></infinite-loading>
                    </dropdown-menu>
                  </dropdown>
                  <input hidden name="discount_id" data-vv-validate-on="none" v-model="discount" v-validate="'required'" />
                  <div class="warning-message" v-if="errors.has('discount_id')">
                    <span>{{ errors.first('discount_id') }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Category Id</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="select-groups-container field-item">
                <div class="select">
                  <dropdown style="width:100%">
                    <p class="active-selected capitalize" @click="resetErrors()" v-if="category">
                      {{ `${_.get(category, 'name', '')}` }}
                    </p>
                    <p class="active-selected" @click="resetErrors()" v-else>{{ $t('common.please_select') }}</p>
                    <dropdown-menu slot="dropdown" class="box-popup" style="height: 200px; overflow-y: auto;">
                      <dropdown-item
                        class="dropdown-item capitalize"
                        v-for="(item, index) in categories"
                        :key="index"
                        @click.native="setCategory(item)"
                        :title="_.get(item, 'code', '')"
                      >
                        {{ `${_.get(item, 'name', '')}` }}
                      </dropdown-item>
                      <infinite-loading force-use-infinite-wrapper=".box-popup" @infinite="loadMoreCategory"></infinite-loading>
                    </dropdown-menu>
                  </dropdown>
                  <input hidden name="category_id" data-vv-validate-on="none" v-model="category" v-validate="'required'" />
                  <div class="warning-message" v-if="errors.has('category_id')">
                    <span>{{ errors.first('category_id') }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="field is-clearfix" style="margin: auto;">
        <input v-reset-error type="button" class="button is-link is-pulled-right is-normal" value="Update" @click="controlEditProduct">
      </div>
    </div>
  </div>
</template>

<script>
  import DragDropFile from "../../components/DragDropFile";
  import moment from 'moment';

  export default {
    name: "AddProductPage",
    components: { DragDropFile },
    data() {
      return {
        params: {
          rate: 0,
          rate_amount: 0,
        },
        files: [],
        urlImage: '',
        discounts: [],
        categories: [],
        discount: null,
        category: null,
        pageDiscount: 1,
        pageCategory: 1,
      }
    },
    computed: {
      showOverlay() {
        return this.files instanceof File || this.files instanceof FileList || !!this.urlImage;
      },
    },
    async mounted () {
      console.log(this.$route.params, "this.$router.params");
      console.log(this.$route, "this.$router.params");
      const { data } = await this.rf.getRequest('ProductRequest').getProduct({ id: this.$route.params.id });
      this.params = {
        ...this.params,
        ...data
      };
      this.$set(this.params, 'special_to', moment(this.params.special_to).format('YYYY-MM-DD'));
      this.$set(this.params, 'special_from', moment(this.params.special_from).format('YYYY-MM-DD'));
      this.category = this.params.category;
      this.discount = this.params.discount;
      this.urlImage = this.params.image_url;
    },
    methods: {
      async controlEditProduct () {
        try {
          const formData = new FormData();

          formData.append('img_product', this.files);
          _.forEach(Object.keys(this.params), (k) => {
            formData.append(k, this.params[k]);
          });

          const data = await this.rf.getRequest('ProductRequest').updateProduct(formData);
          if (data) {
            this.showSuccess('Created Product Success');
          }
        } catch (e) {
          this.showError('Created Product Failed')
        }
      },
      async loadMoreDiscount ($state) {
        try {
          const { data } = await this.rf.getRequest('ProductRequest').getDiscounts({ page: this.pageDiscount++, limit: 10 });
          this.discounts.push(...data.data);
          if (!data || data.last_page === data.current_page) {
            $state.complete();
          } else {
            $state.loaded();
          }
        } catch (e) {
          $state.complete();
        }
      },
      setDiscount (item) {
        this.discount = item;
        this.params.discount_id = item.id;
      },
      setCategory (item) {
        this.category = item;
        this.params.category_id = item.id;
      },
      async loadMoreCategory ($state) {
        try {
          const { data } = await this.rf.getRequest('CategoryRequest').getCategories({ page: this.pageCategory++, limit: 10 });
          this.categories.push(...data.data);
          if (!data || data.last_page === data.current_page) {
            $state.complete();
          } else {
            $state.loaded();
          }
        } catch (e) {
          $state.complete();
        }
      },
      showImageBase64(base64Image) {
        console.log(base64Image, "base64Image");
        this.urlImage = base64Image;
      },
      removeImage() {
        this.urlImage = '';
        this.files = '';
      },
    }
  }
</script>

<style scoped lang="scss">
  .custom-drag-drop {
    height: 250px;
  }

  .has-border {
    border: solid thin #cccccc !important;
  }
</style>