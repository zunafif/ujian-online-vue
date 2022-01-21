<template>
  <div>
    <h1><center>Section Khusus : {{ listDosen[0].nama_dosen }}</center></h1>
    <el-divider />
    <el-table :data="listDosen" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="No" prop="id" width="50">
        <template slot-scope="scope">
          <span>{{ scope.$index+1 }}</span>
        </template>
      </el-table-column>

      <el-table-column align="Left" label="Nama Dosen">
        <template slot-scope="scope">
          <span>{{ scope.row.nama_dosen }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="No. Telp">
        <template slot-scope="scope">
          <span>{{ scope.row.no_telp }}</span>
        </template>
      </el-table-column>

    </el-table>
    <el-divider />
  </div>
</template>

<script>
export default {
  name: 'DetailDosen',
  props: ['listDosen'],
  data() {
    return {
      form_detail: false,
    };
  },
};
</script>

