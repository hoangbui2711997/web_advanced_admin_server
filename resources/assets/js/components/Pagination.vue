<template>
  <div>
    <div class="VuePagination">
      <ul
        v-show="totalPages > 1"
        style="margin: 0px; margin-right: -11px;"
        class="pagination VuePagination__pagination">

        <!--<li class="VuePagination__pagination-item page-item VuePagination__pagination-item-prev-chunk "-->
        <!--:class="{disabled : !allowedChunk(-1)}">-->
        <!--<a class="page-link" href="javascript:void(0);"-->
        <!--@click="setChunk(-1)">&lt;&lt;</a>-->
        <!--</li>-->


        <li
          :class="{disabled : !allowedPage(page - 1)}"
          class="VuePagination__pagination-item page-item VuePagination__pagination-item-prev-page page-prev">
          <a
            class="page-link "
            href="javascript:void(0);"
            @click="prev()">{{ $t('previous') }}</a>
        </li>

        <template v-for="(item, index) in pages">
          <li
            :key="index"
            :class="{active: parseInt(page) === parseInt(item)}"
            class="VuePagination__pagination-item page-item ">
            <a
              class="page-link"
              role="button"
              @click="setPage(item)">{{ item }}</a>
          </li>
        </template>

        <li
          :class="{disabled : !allowedPage(page + 1)}"
          class="VuePagination__pagination-item page-item VuePagination__pagination-item-next-page page-next">
          <a
            class="page-link"
            href="javascript:void(0);"
            @click="next()">{{ $t('next') }}</a>
        </li>

        <!--<li class="VuePagination__pagination-item page-item VuePagination__pagination-item-next-chunk "-->
        <!--:class="{disabled : !allowedChunk(1)}">-->
        <!--<a class="page-link" href="javascript:void(0);"-->
        <!--@click="setChunk(1)">&gt;&gt;</a>-->
        <!--</li>-->
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      pageParent: {
        type: Number,
        default: 1,
      },
      records: {
        type: Number,
        required: true,
      },
      chunk: {
        type: Number,
        required: false,
        default: 6,
      },
      perPage: {
        type: Number,
        required: true,
      },
    },
    data () {
      return {
        page: this.pageParent,
        isPage: 0,
      };
    },
    computed: {
      pages () {
        if (!this.records) {
          return [];
        }

        return range(Math.max((this.page - 1) % (this.chunk - 1) === 0 ? this.page : (this.page - (this.page - 1) % (this.chunk - 1)), 1), this.chunk, this.totalPages);
      },
      totalPages () {
        return this.records ? Math.ceil(this.records / this.perPage) : 1;
      },
      totalChunks () {
        return Math.ceil(this.totalPages / this.chunk);
      },
      currentChunk () {
        return Math.ceil(this.page / this.chunk);
      },
      paginationStart () {
        const rawValue = ((this.currentChunk - 1) * this.chunk) + 1;
        if (this.page === (rawValue + this.chunk - 1)) {
          return rawValue + 1;
        }
        return rawValue;
      },
      pagesInCurrentChunk () {
        return this.paginationStart + this.chunk <= this.totalPages ? this.chunk : this.totalPages - this.paginationStart + 1;
      },
    },
    watch: {
      records () {
        if (this.page > this.totalPages) {
          this.page = 1;
        }
      },
      pageParent () {
        this.page = this.pageParent;
      },
    },
    methods: {
      setPage (page) {
        if (this.allowedPage(page)) {
          this.paginate(page);
          this.isPage = page;
        }
      },
      paginate (page) {
        this.page = page;
        this.$emit('Pagination:page', page);
      },
      next () {
        return this.setPage(this.page + 1);
      },
      prev () {
        return this.setPage(this.page - 1);
      },
      setChunk (direction) {
        this.setPage((((this.currentChunk - 1) + direction) * this.chunk) + 1);
      },
      allowedPage (page) {
        return page >= 1 && page <= this.totalPages;
      },
      allowedChunk (direction) {
        return (parseInt(direction) === 1 && this.currentChunk < this.totalChunks) ||
          (parseInt(direction) === -1 && this.currentChunk > 1);
      },
      allowedPageClass (direction) {
        return this.allowedPage(direction) ? '' : 'disabled';
      },
      allowedChunkClass (direction) {
        return this.allowedChunk(direction) ? '' : 'disabled';
      },
      activeClass (page) {
        return parseInt(this.page) === parseInt(page) ? 'active' : '';
      },
    },
  };

  function range (start, chunk, total) {
    if (start + chunk > total) {
      start = Math.max(total - chunk + 1, 1);
    }
    const end = chunk > total ? total : chunk;
    return Array.apply(0, Array(end))
      .map((element, index) => index + start);
  }
</script>

<style lang="scss" scoped>
  .page-item {
    &.active {
      a {
        background-color: #1ea1f2;
        color: white;
      }
    }
    a {
      position: relative;
      float: left;
      padding: 5px 12px;
      width: auto;
      line-height: 1.6;
      text-decoration: none;
      color: black;
      background-color: #fff;
      border: 1px solid #ddd;
      margin-left: -1px;
      border-radius: 4px;
      margin-right: 9px;
      font-size: 13px;
      height: 31px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      &:hover {
        background-color: #fff;
        color: black;
      }
      &:link {
        background-color: #fff;
        color: black;
      }
    }
  }
  .page-prev {
    a {
      transform: scale(0.9,1);
      // &:before {
      //     content: "\e257";
      // }
    }
  }
  .page-next {
    a {
      transform: scale(0.9,1);
      // &:before {
      //     content: "\e258";
      // }
    }
  }
</style>
