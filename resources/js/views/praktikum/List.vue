<template>
  <div class="app-container">
    <div>
      <FilenameOption v-model="filename" />
      <AutoWidthOption v-model="autoWidth" />
      <BookTypeOption v-model="bookType" />
      <el-button :loading="downloadLoading" style="margin:0 0 20px 20px;" type="primary" icon="document" @click="handleDownload">
        {{ $t('excel.export') }} Excel
      </el-button>
    </div>
    <div class="filter-container">
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleSearchForm">
        {{ $t('Pencarian Lanjutan') }}
      </el-button>
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" sortable="custom" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="Left" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.nama }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Periode" width="185">
        <template slot-scope="scope">
          <span>{{ scope.row.periode }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Kode" width="150">
        <template slot-scope="scope">
          <span>{{ scope.row.kode }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Status" width="100">
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Dosen Pengampu" width="150">
        <template slot-scope="scope">
          <span>{{ scope.row.nama_dosen }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="235">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEditForm(scope.row.id, scope.row.nama);" />
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.nama);" />
          <el-button type="success" size="small" icon="el-icon-view" @click="handleSelect(scope.row.id)" />
        </template>
      </el-table-column>
    </el-table>

    <!-- Pndah Halaman Tabel -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <!-- Tambah Data -->
    <el-dialog :title="'Tambah Praktikum'" :visible.sync="praktikumFormVisible">
      <div class="form-container">
        <el-form ref="praktikumForm" :model="currentPraktikum" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Nama" prop="nama">
            <el-input v-model="currentPraktikum.nama" />
          </el-form-item>
          <el-form-item label="Periode" prop="periode">
            <el-input v-model="currentPraktikum.periode" />
          </el-form-item>
          <el-form-item label="Kode" prop="kode">
            <el-input v-model="currentPraktikum.kode" />
          </el-form-item>
          <el-form-item :label="$t('table.status')">
            <el-select v-model="currentPraktikum.status" class="filter-item" placeholder="Please select">
              <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item" />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('Dosen Pengampu')">
            <el-select v-model="currentPraktikum.id_dosen" class="filter-item" placeholder="Please select" prop="id_dosen">
              <el-option v-for="item in dosen" :key="item.id" :label="item.nama_dosen" :value="item.id" />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="praktikumFormVisible = false">
            Cancel
          </el-button>
          <el-button type="primary" @click="handleSubmit()">
            Confirm
          </el-button>
        </div>
      </div>
    </el-dialog>

    <!-- Filter lanjutan -->
    <el-dialog :title="'Pencarian Lanjutan'" :visible.sync="searchFormVisible">
      <div class="form-container">
        <el-form ref="praktikumForm" :model="currentPraktikum" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-input v-model="query.nama" placeholder="Praktikum" style="width: 200px;" class="filter-item" />
          <el-select v-model="query.status" :placeholder="$t('table.status')" clearable style="width: 90px" class="filter-item">
            <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item" />
          </el-select>
        </el-form>
        <br>
        <br>
        <div slot="footer" class="dialog-footer">
          <el-button @click="searchFormVisible = false">
            Cancel
          </el-button>
          <el-button type="primary" @click="handleFilter()">
            Search
          </el-button>
        </div>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="detailForm">
      <detail v-if="detailForm" :list-detail="listDetail" @coba="coba" />
    </el-dialog>
    <el-dialog :visible.sync="detailHilang">
      <p>{{ list[0].nama }}</p>
    </el-dialog>
  </div>
</template>

<script>
import FilenameOption from './components/FilenameOption';
import AutoWidthOption from './components/AutoWidthOption';
import BookTypeOption from './components/BookTypeOption';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import waves from '@/directive/waves';
import detail from './detail';
const praktikumResource = new Resource('praktikum');
const detailResource = new Resource('getPraktikum');
const dosenResource = new Resource('dosen');

export default {
  name: 'PraktikumList',
  components: { Pagination, FilenameOption, AutoWidthOption, BookTypeOption, detail },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        aktif: 'success',
        nonaktif: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      downloadLoading: false,
      filename: undefined,
      autoWidth: true,
      bookType: 'xlsx',
      total: 0,
      detailForm: false,
      detailHilang: false,
      listDetail: null,
      tableKey: 0,
      list: {},
      dosen: null,
      formTitle: '',
      listLoading: true,
      praktikumFormVisible: false,
      searchFormVisible: false,
      currentPraktikum: {},
      statusOptions: ['aktif', 'nonaktif'],
      query: {
        limit: 5,
        page: 1,
        nama: '',
        periode: undefined,
        kode: undefined,
        status: undefined,
        id_dosen: undefined,
      },
    };
  },
  created() {
    this.getList();
    this.getDosen();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.listLoading = true;
      const { data, meta } = await praktikumResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.listLoading = false;
    },
    async getDosen() {
      const { data } = await dosenResource.list({});
      this.dosen = data;
      this.listLoading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    sortChange(data) {
      const { prop, order } = data;
      if (prop === 'id') {
        this.sortByID(order);
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.query.sort = '+id';
      } else {
        this.query.sort = '-id';
      }
      this.handleFilter();
    },
    handleSubmit() {
      if (this.currentPraktikum.id !== null) {
        praktikumResource.update(this.currentPraktikum.id, this.currentPraktikum).then(response => {
          this.$message({
            type: 'success',
            message: 'Praktikum info has been updated successfully',
            duration: 5 * 1000,
          });
          this.getList();
        }).catch(error => {
          console.log(error);
        }).finally(() => {
          this.praktikumFormVisible = false;
        });
      } else {
        praktikumResource
          .store(this.currentPraktikum)
          .then(response => {
            this.$message({
              message: 'New Praktikum ' + this.currentPraktikum.nama + ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentPraktikum = {
              id_dosen: '',
              nama: '',
              kode: '',
              periode: '',
              status: '',
            };
            this.praktikumFormVisible = false;
            this.getList();
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    handleCreateForm() {
      this.praktikumFormVisible = true;
      this.currentPraktikum = {
        id: null,
        id_dosen: '',
        nama: '',
        periode: '',
        kode: '',
        status: '',
      };
    },
    handleSearchForm() {
      this.searchFormVisible = true;
      this.currentPraktikum = {
        id: null,
        id_dosen: '',
        nama: '',
        periode: '',
        kode: '',
        status: '',
      };
    },
    handleDelete(id, nama) {
      this.$confirm('This will permanently delete praktikum ' + nama + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        praktikumResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Delete completed',
          });
          this.getList();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Delete canceled',
        });
      });
    },
    handleEditForm(id) {
      this.formTitle = 'Edit praktikum';
      this.currentPraktikum = this.list.find(praktikum => praktikum.id === id);
      this.praktikumFormVisible = true;
    },
    handleDownload() {
      this.downloadLoading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['Id', 'Praktikum', 'Periode', 'Kode', 'Status', 'Dosen Pengampu'];
        const filterVal = ['index', 'nama', 'periode', 'kode', 'status', 'nama_dosen'];
        const list = this.list;
        const data = this.formatJson(filterVal, list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: this.filename,
          autoWidth: this.autoWidth,
          bookType: this.bookType,
        });
        this.downloadLoading = false;
      });
    },
    coba() {
      this.detailForm = false;
      this.detailHilang = true;
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    async handleSelect(id){
      this.detailForm = true;
      const { data } = await detailResource.get(id);
      this.listDetail = data;
    },
  },
};
</script>

