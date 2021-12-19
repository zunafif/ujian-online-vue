<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.nama" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @sort-change="sortChange"
    >
      <el-table-column align="center" label="ID" prop="id" sortable="custom" width="63">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="Left" label="Modul" width="300">
        <template slot-scope="scope">
          <span>{{ scope.row.nama_modul }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Jumlah Bab" width="150">
        <template slot-scope="scope">
          <span>{{ scope.row.jumlah_bab }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="Materi" width="350">
        <template slot-scope="scope">
          <span>{{ scope.row.materi }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="235">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEditForm(scope.row.id, scope.row.nama);">
            Edit
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.nama);">
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="'Tambah Modul'" :visible.sync="modulFormVisible">
      <div class="form-container">
        <el-form ref="modulForm" :model="currentModul" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="Modul" prop="nama_modul">
            <el-input v-model="currentModul.nama_modul" />
          </el-form-item>
          <el-form-item label="Jumlah Bab" prop="jumlah_bab">
            <el-input v-model="currentModul.jumlah_bab" />
          </el-form-item>
          <el-form-item label="Materi" prop="materi">
            <el-input v-model="currentModul.materi" type="textarea" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="modulFormVisible = false">
            Cancel
          </el-button>
          <el-button type="primary" @click="handleSubmit()">
            Confirm
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import waves from '@/directive/waves';
const modulResource = new Resource('modul');

export default {
  name: 'ModulList',
  directives: { waves },
  data() {
    return {
      tableKey: 0,
      list: null,
      formTitle: '',
      listLoading: true,
      modulFormVisible: false,
      currentModul: {},
      listQuery: {
        page: 1,
        limit: 20,
        nama_modul: undefined,
        sort: '+id',
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await modulResource.list({});
      this.list = data;
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
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
        this.listQuery.sort = '+id';
      } else {
        this.listQuery.sort = '-id';
      }
      this.handleFilter();
    },
    handleSubmit() {
      if (this.currentModul.id !== null) {
        modulResource.update(this.currentModul.id, this.currentModul).then(response => {
          this.$message({
            type: 'success',
            message: 'Modul info has been updated successfully',
            duration: 5 * 1000,
          });
          this.getList();
        }).catch(error => {
          console.log(error);
        }).finally(() => {
          this.modulFormVisible = false;
        });
      } else {
        modulResource
          .store(this.currentModul)
          .then(response => {
            this.$message({
              message: 'New Modul ' + this.currentModul.nama_modul + ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentModul = {
              nama_modul: '',
              jumlah_bab: '',
              materi: '',
            };
            this.modulFormVisible = false;
            this.getList();
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    handleCreateForm() {
      this.modulFormVisible = true;
      this.currentModul = {
        id: null,
        nama_modul: '',
        jumlah_bab: '',
        materi: '',
      };
    },
    handleDelete(id, nama_modul) {
      this.$confirm('This will permanently delete praktikum ' + nama_modul + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        modulResource.destroy(id).then(response => {
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
      this.formTitle = 'Edit Modul';
      this.currentModul = this.list.find(modul => modul.id === id);
      this.modulFormVisible = true;
    },
  },
};
</script>

