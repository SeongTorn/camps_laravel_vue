import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  camps: [],
  post: Cookies.get('post') ? JSON.parse(Cookies.get('post')) : null,
  camp_id: Cookies.get('camp_id'),
  parent: Cookies.get('parent') ? JSON.parse(Cookies.get('parent')) : null,
  children: Cookies.get('children') ? JSON.parse(Cookies.get('children')) : [],
  enrols: Cookies.get('enrols') ? JSON.parse(Cookies.get('enrols')) : [],
}

// getters
export const getters = {
  camps: state => state.camps,
  camp_id: state => state.camp_id,
  post: state => state.post,
  parent: state => state.parent,
  children: state => state.children,
  enrols: state => state.enrols
}

// mutations
export const mutations = {
  [types.SET_POST] (state, { post }) {
    state.post = post
    Cookies.set('post', post, { expires: 365 })
  },
  [types.SET_CAMPS] (state, { camps }) {
    state.camps = camps
  },
  [types.SET_CAMPID] (state, { camp_id }) {
    state.camp_id = camp_id
    Cookies.set('camp_id', camp_id, { expires: 365 })
  },
  [types.INIT_CAMPS] (state) {
    state.camps = null
  },
  [types.SET_PARENT] (state, { parent }) {
    state.parent = parent
    Cookies.set('parent', parent, { expires: 365 })
  },
  [types.ADD_CHILD] (state, { child }) {
    state.children.push(child)
    Cookies.set('children', state.children, { expires: 365 })
  },
  [types.REMOVE_CHILD] (state, { child_id }) {
    if (child_id == -1) {
      state.children = []
    } else {
      state.children = state.children.filter(child => {
        return child.id != child_id
      })
    }
    Cookies.set('children', state.children, { expires: 365 })
  },
  [types.SET_CHILDREN] (state, { children }) {
    state.children = children
    Cookies.set('children', state.children, { expires: 365 })
  },
  [types.INIT_CHILDREN] (state) {
    state.children = []
    Cookies.set('children', state.children, { expires: 365 })
  },
  [types.ADD_ENROL] (state, { enrol }) {
    state.enrols.push(enrol)
    Cookies.set('enrols', state.enrols, { expires: 365 })
  },
  [types.REMOVE_ENROLS] (state) {
    state.enrols = []
    Cookies.set('enrols', state.enrols, { expires: 365 })
  },
  [types.SET_ENROL] (state, { enrol }) {
    state.enrols[enrol.id].camp_id = enrol.camp_id;
    state.enrols[enrol.id].camp_name = enrol.camp_name;
    state.enrols[enrol.id].fee = enrol.fee;
    state.enrols[enrol.id].selecting = false;
    state.enrols[enrol.next_id].selecting = true;
    Cookies.set('enrols', state.enrols, { expires: 365 })
  },
  [types.INIT_ENROLS] (state, { camp_id }) {
    state.enrols = []
    state.children.filter((child, index) => {
      state.enrols.push({
        id: index,
        child_id: child.id,
        child_name: child.first_name + ' ' + child.last_name,
        camp_id: camp_id,
        camp_name: '',
        selecting: false
      })
      return true
    })
    if (state.enrols.length > 0) {
      state.enrols[0].selecting = true
      state.enrols[0].camp_id = camp_id
    }
    Cookies.set('enrols', state.enrols, { expires: 365 })
  }
}

// actions
export const actions = {
  setPost ({ commit }, post) {
    return new Promise((resolve, reject) => {
      if (post) {
        commit(types.SET_POST, post)
        resolve()
      } else {
        reject()
      }
    })
  },
  setCamps ({ commit }, camps) {
    commit(types.SET_CAMPS, camps)
  },
  setCampId ({ commit }, data) {
    return new Promise((resolve, reject) => {
      if (data.camp_id > 0) {
        commit(types.SET_CAMPID, data)
        resolve()
      } else {
        reject({error: 'camp id is invalid'})
      }
    })
  },
  setParent ({ commit }, parent) {
    return new Promise((resolve, reject) => {
      if (parent) {
        commit(types.SET_PARENT, parent)
        resolve()
      } else {
        reject({error: 'cannot set parent info with null'})
      }
    })
  },
  addChildren ({ commit }, child) {
    commit(types.ADD_CHILD, child)
  },
  removeChild ({ commit }, child_id) {
    commit(types.REMOVE_CHILD, child_id)
  },
  addEnrol ({ commit }, enrol) {
    commit(types.ADD_ENROL, enrol)
  },
  setEnrol ({ commit }, enrol) {
    return new Promise((resolve, reject) => {
      commit(types.SET_ENROL, enrol)
      resolve({index: 1});
    })
  },
  initEnrols ({ commit }, data) {
    return new Promise((resolve, reject) => {
      if (data.camp_id > 0) {
        commit(types.INIT_ENROLS, data)
        resolve()
      } else {
        reject({error: 'camp id is invalid'})
      }
    })
  },
  removeEnrols ({ commit }) {
    return new Promise((resolve, reject) => {
      commit(types.INIT_CHILDREN)
      commit(types.REMOVE_ENROLS)
      commit(types.REMOVE_ENROLS)
      commit(types.INIT_CAMPS)
      commit(types.SET_CAMPID, {camp_id: 0})
      resolve()
    })
  },
  initChildren({ commit }) {
    commit(types.INIT_CHILDREN)
  },
  async fetchLocationCamps ({ commit }, post_id) {
    try {
      const { data } = await axios.post('/api/camps', { post_id: post_id })
      if (data.success == true) {
        commit(types.SET_CAMPS, { camps: data.data })
      } else {
        window.location.href = data.redirect_url
      }
    } catch (e) {
      commit(types.INIT_CAMPS)
    }
  },
  fetchChildren ({ commit }, parent_id) {
    return new Promise((resolve, reject) => {
      axios.post('/api/children', {parent_id: parent_id}).then(response => {
        if (response.data.status == "success") {
          commit(types.SET_CHILDREN, { children: response.data.children })
          resolve()
        } else {
          commit(types.INIT_CAMPS)
          reject()
        }
      })
    })
  },
  async fetchParent ({ commit }, email) {
    try {
      const { data } = await axios.post('/api/parent', {email: email})
      if (data.status == "success") {
        commit(types.SET_PARENT, { parent: data.parent })
      }
    } catch (e) {
      commit(types.INIT_CAMPS)
    }
  }
}
