/* eslint-disable */
/* prettier-ignore */
// @ts-nocheck
// Generated by unplugin-vue-router. ‼️ DO NOT MODIFY THIS FILE ‼️
// It's recommended to commit this file.
// Make sure to add this file to your tsconfig.json file as an "includes" or "files" entry.

declare module 'vue-router/auto-routes' {
  import type {
    RouteRecordInfo,
    ParamValue,
    ParamValueOneOrMore,
    ParamValueZeroOrMore,
    ParamValueZeroOrOne,
  } from 'unplugin-vue-router/types'

  /**
   * Route name map generated by unplugin-vue-router
   */
  export interface RouteNamedMap {
    'root': RouteRecordInfo<'root', '/', Record<never, never>, Record<never, never>>,
    '$error': RouteRecordInfo<'$error', '/:error(.*)', { error: ParamValue<true> }, { error: ParamValue<false> }>,
    'activity-log': RouteRecordInfo<'activity-log', '/activity-log', Record<never, never>, Record<never, never>>,
    'auth-login': RouteRecordInfo<'auth-login', '/auth/login', Record<never, never>, Record<never, never>>,
    'auth-login-backup': RouteRecordInfo<'auth-login-backup', '/auth/login-backup', Record<never, never>, Record<never, never>>,
    'permissions': RouteRecordInfo<'permissions', '/permissions', Record<never, never>, Record<never, never>>,
    'roles': RouteRecordInfo<'roles', '/roles', Record<never, never>, Record<never, never>>,
    'system-log': RouteRecordInfo<'system-log', '/system-log', Record<never, never>, Record<never, never>>,
    'system-trash': RouteRecordInfo<'system-trash', '/system-trash', Record<never, never>, Record<never, never>>,
    'testing': RouteRecordInfo<'testing', '/testing', Record<never, never>, Record<never, never>>,
    'under-development': RouteRecordInfo<'under-development', '/under-development', Record<never, never>, Record<never, never>>,
    'user-list': RouteRecordInfo<'user-list', '/user/list', Record<never, never>, Record<never, never>>,
    'user-list-add-new-user-drawer': RouteRecordInfo<'user-list-add-new-user-drawer', '/user/list/AddNewUserDrawer', Record<never, never>, Record<never, never>>,
    'user-list-index-backup': RouteRecordInfo<'user-list-index-backup', '/user/list/index-backup', Record<never, never>, Record<never, never>>,
    'user-view-id': RouteRecordInfo<'user-view-id', '/user/view/:id', { id: ParamValue<true> }, { id: ParamValue<false> }>,
    'website-navigation': RouteRecordInfo<'website-navigation', '/website/navigation', Record<never, never>, Record<never, never>>,
    'website-post': RouteRecordInfo<'website-post', '/website/post', Record<never, never>, Record<never, never>>,
    'website-post-create-edit': RouteRecordInfo<'website-post-create-edit', '/website/post/create-edit', Record<never, never>, Record<never, never>>,
    'website-post-category': RouteRecordInfo<'website-post-category', '/website/post-category', Record<never, never>, Record<never, never>>,
    'website-post-tag': RouteRecordInfo<'website-post-tag', '/website/post-tag', Record<never, never>, Record<never, never>>,
  }
}
