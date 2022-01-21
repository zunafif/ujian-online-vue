<template>
  <div class="app-container">
    <el-button type="primary" icon="el-icon-plus" @click="handleTambah()">
      Tambah Dosen Pengampu
    </el-button><br><br>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row>
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.nama_dosen }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="No. Telp">
        <template slot-scope="scope">
          <span>{{ scope.row.no_telp }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Action">
        <template slot-scope="scope">
          <el-button type="success" icon="el-icon-view" size="small" @click="handleDetail(scope.row.id)" />
          <el-button type="primary" icon="el-icon-edit" size="small" @click="handleDelete(scope.row.id)" />
          <el-button type="danger" icon="el-icon-delete" size="small" @click="handleDelete(scope.row.id, scope.row.nama_dosen)" />
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="form_detail">
      <detail-dosen v-if="form_detail" :list-dosen="listDosen" />
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import detailDosen from './detailDosen';
const dosenResource = new Resource('dosen');
const detailResource = new Resource('getDosen');

export default {
  name: 'DosenList',
  components: { detailDosen },
  data() {
    return {
      list: [],
      loading: true,
      id_modul: null,
      form_detail: false,
      listDosen: [],
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await dosenResource.list({});
      this.list = data;
      this.loading = false;
    },

    handleDelete(id, nama_dosen) {
      this.$confirm('This will permanently delete Dosen ' + nama_dosen + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        dosenResource.destroy(id).then(response => {
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

    async handleDetail(id) {
      this.form_detail = true;
      const { data } = await detailResource.get(id);
      this.listDosen = data;
    },
  },
};
</script>
