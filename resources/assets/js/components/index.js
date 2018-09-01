import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'
import TopSpace from './TopSpace'
import BottomSpace from './BottomSpace'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globaly.
[
  Card,
  Child,
  Button,
  Checkbox,
  HasError,
  AlertError,
  AlertSuccess,
  TopSpace,
  BottomSpace
].forEach(Component => {
  Vue.component(Component.name, Component)
})
