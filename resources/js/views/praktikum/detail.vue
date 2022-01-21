<template>
  <div>
    <h2><center>{{ listDetail[0].nama }}</center></h2>
    <el-divider />
    <el-table :data="listDetail" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="50">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="Left" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.nama }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Periode" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.periode }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Kode" width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.kode }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Status" width="80">
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
    </el-table>
    <el-divider />
    <div>
      <el-button type="warning" icon="el-icon-document" @click="$emit('coba')">Download PDF</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Detail',
  filters: {
    statusFilter(status) {
      const statusMap = {
        aktif: 'success',
        nonaktif: 'danger',
      };
      return statusMap[status];
    },
  },
  props: ['listDetail'],
  data() {
    return {
      pdfVisible: false,
    };
  },
};
</script>
