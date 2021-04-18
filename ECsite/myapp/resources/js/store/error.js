const state = {
  code: null,
  testData1: null,
  testData2: null,
  testData3: null
}

const mutations = {
  setCode(state, code){
    state.code = code;
  },
  setTestData1(state, testData1){
    state.testData1 = testData1;
  },
  setTestData2(state, testData2){
    state.testData2 = testData2;
  },
  setTestData3(state, testData3){
    state.testData3 = testData3;
  }
}

export default{
  namespaced: true,
  state,
  mutations
}