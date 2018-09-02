import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  camps: null,
  location: Cookies.get('location') ? JSON.parse(Cookies.get('location')) : null,
  camp_id: Cookies.get('camp_id'),
  parent: Cookies.get('parent') ? JSON.parse(Cookies.get('parent')) : null,
  children: []
}

// getters
export const getters = {
  camps: state => state.camps,
  camp_id: state => state.camp_id,
  location: state => state.location,
  parent: state => state.parent,
  children: state => state.children
}

// mutations
export const mutations = {
  [types.SET_LOCATION] (state, { location }) {
    state.location = location
    Cookies.set('location', location, { expires: 365 })
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
  [types.ADD_CHILDREN] (state, { child }) {
    state.children.push(child)
  },
  [types.SET_CHILDREN] (state, { children }) {
    state.children = children
  },
}

// actions
export const actions = {
  setLocation ({ commit }, location) {
    commit(types.SET_LOCATION, location)
  },
  setCamps ({ commit }, camps) {
    commit(types.SET_CAMPS, camps)
  },
  setCampId ({ commit }, camp_id) {
    commit(types.SET_CAMPID, camp_id)
  },
  setParent ({ commit }, parent) {
    commit(types.SET_PARENT, parent)
  },
  addChildren ({ commit }, child) {
    console.log(child)
    commit(types.ADD_CHILDREN, child)
  },
  async fetchLocationCamps ({ commit }, post_id) {
    try {
      const { data } = await axios.post('/api/camps', {post_id: post_id})
      commit(types.SET_CAMPS, { camps: data })
    } catch (e) {
      commit(types.INIT_CAMPS)
    }
  },
  async fetchChildren ({ commit }, parent_id) {
    try {
      const { data } = await axios.post('/api/children', {parent_id: parent_id})
      if (data.status == "success") {
        commit(types.SET_CHILDREN, { children: data.children })
      }
    } catch (e) {
      commit(types.INIT_CAMPS)
    }
  }
}
