import{u as ne,o as g,c as R,w as A,a as w,b as f,t as tt,d as S,V as oe,e as ie,f as se,h as M,F as xt,r as bt,i as re,j as le,k as At,l as ae,m as O,n as It,p as ce,q as Ct,s as Z,v as gt,x as ue,y as V,z as j,A as zt,B as et,C as Rt,D as B,E as fe,G as de,H as pe,I as me,J as he,K as P,L as mt,M as Pt,N as Ot,T as ge,O as ve,P as ye,Q as we}from"./app-DnuOV1kV.js";import xe from"./Footer-zxFYutN4.js";import be from"./NavbarThemeSwitcher-3ZKFcDc4.js";import{_ as Ae}from"./UserProfile-hkU5sNiO.js";import{c as Ce,a as Re}from"./casl-GkGm6mS8.js";import"./VBadge-Bpn9vPa4.js";const ke="/build/assets/en-DzoK8HxE.png",_e="/build/assets/ar-C3qD-OPR.png",Le={class:"d-flex flex-row gap-3",style:{"align-items":"center"}},Se=["src"],Ne={style:{color:"#374557","font-size":"18px","font-weight":"600"}},Ee={__name:"I18n",props:{languages:{type:Array,required:!0},location:{type:null,required:!1,default:"bottom end"}},setup(t){const e=t,{locale:o}=ne({useScope:"global"});return(n,i)=>(g(),R(ae,{variant:"default"},{default:A(()=>[w("div",Le,[w("img",{src:f(o)=="en"?f(ke):f(_e),width:"24px",height:"24px",style:{"border-radius":"50%"},alt:""},null,8,Se),w("span",Ne,tt(n.$t("lang")),1),S(oe,{color:"#9A9A9A",icon:"tabler-caret-down-filled"})]),S(ie,{activator:"parent",location:e.location,offset:"12px",width:"175"},{default:A(()=>[S(se,{selected:[f(o)],color:"primary"},{default:A(()=>[(g(!0),M(xt,null,bt(e.languages,s=>(g(),R(re,{key:s.i18nLang,value:s.i18nLang,onClick:r=>o.value=s.i18nLang},{default:A(()=>[S(le,null,{default:A(()=>[At(tt(s.label),1)]),_:2},1024)]),_:2},1032,["value","onClick"]))),128))]),_:1},8,["selected"])]),_:1},8,["location"])]),_:1}))}},Te={class:"nav-items"},Pe={__name:"HorizontalNav",props:{navItems:{type:null,required:!0}},setup(t){const e=o=>"children"in o?$e:Ht;return(o,n)=>(g(),M("ul",Te,[(g(!0),M(xt,null,bt(t.navItems,(i,s)=>(g(),R(O(e(i)),{key:s,item:i},null,8,["item"]))),128))]))}},Oe={class:"nav-group-label"},$e=Object.assign({name:"HorizontalNavGroup"},{__name:"HorizontalNavGroup",props:{item:{type:null,required:!0},childrenAtEnd:{type:Boolean,required:!1,default:!1},isSubItem:{type:Boolean,required:!1,default:!1}},setup(t){const e=t,o=It(),n=ce(),i=Ct(),s=Z(!1);return gt(()=>o.path,()=>{const r=ue(e.item.children,n);s.value=r},{immediate:!0}),(r,l)=>f(Ce)(t.item)?(g(),R(f(Rn),{key:0,"is-rtl":f(i).isAppRTL,class:et(["nav-group",[{active:f(s),"children-at-end":t.childrenAtEnd,"sub-item":t.isSubItem,disabled:t.item.disable}]]),tag:"li","content-container-tag":"ul","popper-inline-end":t.childrenAtEnd},{content:A(()=>[(g(!0),M(xt,null,bt(t.item.children,a=>(g(),R(O("children"in a?"HorizontalNavGroup":f(Ht)),{key:a.title,item:a,"children-at-end":"","is-sub-item":""},null,8,["item"]))),128))]),default:A(()=>[w("div",Oe,[(g(),R(O(f(V).app.iconRenderer||"div"),j({class:"nav-item-icon"},t.item.icon||f(V).verticalNav.defaultNavItemIconProps),null,16)),(g(),R(O(f(V).app.i18n.enable?"i18n-t":"span"),j(f(zt)(t.item.title,"span"),{class:"nav-item-title"}),{default:A(()=>[At(tt(t.item.title),1)]),_:1},16)),(g(),R(O(f(V).app.iconRenderer||"div"),j(f(V).icons.chevronDown,{class:"nav-group-arrow"}),null,16))])]),_:1},8,["is-rtl","class","popper-inline-end"])):Rt("",!0)}}),De={class:"layout-navbar"},Ve={class:"navbar-content-container"},Be={class:"layout-horizontal-nav"},Me={class:"horizontal-nav-content-container"},Ie={class:"layout-page-content"},ze={class:"layout-footer"},He={class:"footer-content-container"},Fe={__name:"HorizontalNavLayout",props:{navItems:{type:null,required:!0}},setup(t){const e=Ct();return(o,n)=>(g(),M("div",{class:et(["layout-wrapper",f(e)._layoutClasses])},[w("div",{class:et(["layout-navbar-and-nav-container",f(e).isNavbarBlurEnabled&&"header-blur"])},[w("div",De,[w("div",Ve,[B(o.$slots,"navbar")])]),w("div",Be,[w("div",Me,[S(f(Pe),{"nav-items":t.navItems},null,8,["nav-items"])])])],2),w("main",Ie,[B(o.$slots,"default")]),w("footer",ze,[w("div",He,[B(o.$slots,"footer")])])],2))}},Ht={__name:"HorizontalNavLink",props:{item:{type:null,required:!0},isSubItem:{type:Boolean,required:!1,default:!1}},setup(t){const e=t;return(o,n)=>f(Re)(t.item.action,t.item.subject)?(g(),M("li",{key:0,class:et(["nav-link",[{"sub-item":e.isSubItem,disabled:t.item.disable}]])},[(g(),R(O(t.item.to?"RouterLink":"a"),j(f(de)(t.item),{class:{"router-link-active router-link-exact-active":f(fe)(t.item,o.$router)}}),{default:A(()=>[(g(),R(O(f(V).app.iconRenderer||"div"),j({class:"nav-item-icon"},t.item.icon||f(V).verticalNav.defaultNavItemIconProps),null,16)),(g(),R(O(f(V).app.i18n.enable?"i18n-t":"span"),j({class:"nav-item-title"},f(zt)(t.item.title,"span")),{default:A(()=>[At(tt(t.item.title),1)]),_:1},16))]),_:1},16,["class"]))],2)):Rt("",!0)}},vt=Math.min,G=Math.max,st=Math.round,I=t=>({x:t,y:t}),We={left:"right",right:"left",bottom:"top",top:"bottom"},qe={start:"end",end:"start"};function $t(t,e,o){return G(t,vt(e,o))}function at(t,e){return typeof t=="function"?t(e):t}function W(t){return t.split("-")[0]}function ct(t){return t.split("-")[1]}function Ft(t){return t==="x"?"y":"x"}function Wt(t){return t==="y"?"height":"width"}function Y(t){return["top","bottom"].includes(W(t))?"y":"x"}function qt(t){return Ft(Y(t))}function je(t,e,o){o===void 0&&(o=!1);const n=ct(t),i=qt(t),s=Wt(i);let r=i==="x"?n===(o?"end":"start")?"right":"left":n==="start"?"bottom":"top";return e.reference[s]>e.floating[s]&&(r=rt(r)),[r,rt(r)]}function Ge(t){const e=rt(t);return[yt(t),e,yt(e)]}function yt(t){return t.replace(/start|end/g,e=>qe[e])}function Xe(t,e,o){const n=["left","right"],i=["right","left"],s=["top","bottom"],r=["bottom","top"];switch(t){case"top":case"bottom":return o?e?i:n:e?n:i;case"left":case"right":return e?s:r;default:return[]}}function Ye(t,e,o,n){const i=ct(t);let s=Xe(W(t),o==="start",n);return i&&(s=s.map(r=>r+"-"+i),e&&(s=s.concat(s.map(yt)))),s}function rt(t){return t.replace(/left|right|bottom|top/g,e=>We[e])}function Ke(t){return{top:0,right:0,bottom:0,left:0,...t}}function Je(t){return typeof t!="number"?Ke(t):{top:t,right:t,bottom:t,left:t}}function lt(t){const{x:e,y:o,width:n,height:i}=t;return{width:n,height:i,top:o,left:e,right:e+n,bottom:o+i,x:e,y:o}}function Dt(t,e,o){let{reference:n,floating:i}=t;const s=Y(e),r=qt(e),l=Wt(r),a=W(e),c=s==="y",d=n.x+n.width/2-i.width/2,u=n.y+n.height/2-i.height/2,m=n[l]/2-i[l]/2;let p;switch(a){case"top":p={x:d,y:n.y-i.height};break;case"bottom":p={x:d,y:n.y+n.height};break;case"right":p={x:n.x+n.width,y:u};break;case"left":p={x:n.x-i.width,y:u};break;default:p={x:n.x,y:n.y}}switch(ct(e)){case"start":p[r]-=m*(o&&c?-1:1);break;case"end":p[r]+=m*(o&&c?-1:1);break}return p}const Qe=async(t,e,o)=>{const{placement:n="bottom",strategy:i="absolute",middleware:s=[],platform:r}=o,l=s.filter(Boolean),a=await(r.isRTL==null?void 0:r.isRTL(e));let c=await r.getElementRects({reference:t,floating:e,strategy:i}),{x:d,y:u}=Dt(c,n,a),m=n,p={},h=0;for(let v=0;v<l.length;v++){const{name:y,fn:x}=l[v],{x:b,y:k,data:L,reset:C}=await x({x:d,y:u,initialPlacement:n,placement:m,strategy:i,middlewareData:p,rects:c,platform:r,elements:{reference:t,floating:e}});d=b??d,u=k??u,p={...p,[y]:{...p[y],...L}},C&&h<=50&&(h++,typeof C=="object"&&(C.placement&&(m=C.placement),C.rects&&(c=C.rects===!0?await r.getElementRects({reference:t,floating:e,strategy:i}):C.rects),{x:d,y:u}=Dt(c,m,a)),v=-1)}return{x:d,y:u,placement:m,strategy:i,middlewareData:p}};async function jt(t,e){var o;e===void 0&&(e={});const{x:n,y:i,platform:s,rects:r,elements:l,strategy:a}=t,{boundary:c="clippingAncestors",rootBoundary:d="viewport",elementContext:u="floating",altBoundary:m=!1,padding:p=0}=at(e,t),h=Je(p),y=l[m?u==="floating"?"reference":"floating":u],x=lt(await s.getClippingRect({element:(o=await(s.isElement==null?void 0:s.isElement(y)))==null||o?y:y.contextElement||await(s.getDocumentElement==null?void 0:s.getDocumentElement(l.floating)),boundary:c,rootBoundary:d,strategy:a})),b=u==="floating"?{x:n,y:i,width:r.floating.width,height:r.floating.height}:r.reference,k=await(s.getOffsetParent==null?void 0:s.getOffsetParent(l.floating)),L=await(s.isElement==null?void 0:s.isElement(k))?await(s.getScale==null?void 0:s.getScale(k))||{x:1,y:1}:{x:1,y:1},C=lt(s.convertOffsetParentRelativeRectToViewportRelativeRect?await s.convertOffsetParentRelativeRectToViewportRelativeRect({elements:l,rect:b,offsetParent:k,strategy:a}):b);return{top:(x.top-C.top+h.top)/L.y,bottom:(C.bottom-x.bottom+h.bottom)/L.y,left:(x.left-C.left+h.left)/L.x,right:(C.right-x.right+h.right)/L.x}}const Ue=function(t){return t===void 0&&(t={}),{name:"flip",options:t,async fn(e){var o,n;const{placement:i,middlewareData:s,rects:r,initialPlacement:l,platform:a,elements:c}=e,{mainAxis:d=!0,crossAxis:u=!0,fallbackPlacements:m,fallbackStrategy:p="bestFit",fallbackAxisSideDirection:h="none",flipAlignment:v=!0,...y}=at(t,e);if((o=s.arrow)!=null&&o.alignmentOffset)return{};const x=W(i),b=Y(l),k=W(l)===l,L=await(a.isRTL==null?void 0:a.isRTL(c.floating)),C=m||(k||!v?[rt(l)]:Ge(l)),Lt=h!=="none";!m&&Lt&&C.push(...Ye(l,v,h,L));const te=[l,...C],pt=await jt(e,y),it=[];let Q=((n=s.flip)==null?void 0:n.overflows)||[];if(d&&it.push(pt[x]),u){const F=je(i,r,L);it.push(pt[F[0]],pt[F[1]])}if(Q=[...Q,{placement:i,overflows:it}],!it.every(F=>F<=0)){var St,Nt;const F=(((St=s.flip)==null?void 0:St.index)||0)+1,Tt=te[F];if(Tt)return{data:{index:F,overflows:Q},reset:{placement:Tt}};let U=(Nt=Q.filter(q=>q.overflows[0]<=0).sort((q,$)=>q.overflows[1]-$.overflows[1])[0])==null?void 0:Nt.placement;if(!U)switch(p){case"bestFit":{var Et;const q=(Et=Q.filter($=>{if(Lt){const D=Y($.placement);return D===b||D==="y"}return!0}).map($=>[$.placement,$.overflows.filter(D=>D>0).reduce((D,ee)=>D+ee,0)]).sort(($,D)=>$[1]-D[1])[0])==null?void 0:Et[0];q&&(U=q);break}case"initialPlacement":U=l;break}if(i!==U)return{reset:{placement:U}}}return{}}}};async function Ze(t,e){const{placement:o,platform:n,elements:i}=t,s=await(n.isRTL==null?void 0:n.isRTL(i.floating)),r=W(o),l=ct(o),a=Y(o)==="y",c=["left","top"].includes(r)?-1:1,d=s&&a?-1:1,u=at(e,t);let{mainAxis:m,crossAxis:p,alignmentAxis:h}=typeof u=="number"?{mainAxis:u,crossAxis:0,alignmentAxis:null}:{mainAxis:u.mainAxis||0,crossAxis:u.crossAxis||0,alignmentAxis:u.alignmentAxis};return l&&typeof h=="number"&&(p=l==="end"?h*-1:h),a?{x:p*d,y:m*c}:{x:m*c,y:p*d}}const tn=function(t){return t===void 0&&(t=0),{name:"offset",options:t,async fn(e){var o,n;const{x:i,y:s,placement:r,middlewareData:l}=e,a=await Ze(e,t);return r===((o=l.offset)==null?void 0:o.placement)&&(n=l.arrow)!=null&&n.alignmentOffset?{}:{x:i+a.x,y:s+a.y,data:{...a,placement:r}}}}},en=function(t){return t===void 0&&(t={}),{name:"shift",options:t,async fn(e){const{x:o,y:n,placement:i}=e,{mainAxis:s=!0,crossAxis:r=!1,limiter:l={fn:y=>{let{x,y:b}=y;return{x,y:b}}},...a}=at(t,e),c={x:o,y:n},d=await jt(e,a),u=Y(W(i)),m=Ft(u);let p=c[m],h=c[u];if(s){const y=m==="y"?"top":"left",x=m==="y"?"bottom":"right",b=p+d[y],k=p-d[x];p=$t(b,p,k)}if(r){const y=u==="y"?"top":"left",x=u==="y"?"bottom":"right",b=h+d[y],k=h-d[x];h=$t(b,h,k)}const v=l.fn({...e,[m]:p,[u]:h});return{...v,data:{x:v.x-o,y:v.y-n,enabled:{[m]:s,[u]:r}}}}}};function ut(){return typeof window<"u"}function J(t){return Gt(t)?(t.nodeName||"").toLowerCase():"#document"}function _(t){var e;return(t==null||(e=t.ownerDocument)==null?void 0:e.defaultView)||window}function H(t){var e;return(e=(Gt(t)?t.ownerDocument:t.document)||window.document)==null?void 0:e.documentElement}function Gt(t){return ut()?t instanceof Node||t instanceof _(t).Node:!1}function N(t){return ut()?t instanceof Element||t instanceof _(t).Element:!1}function T(t){return ut()?t instanceof HTMLElement||t instanceof _(t).HTMLElement:!1}function Vt(t){return!ut()||typeof ShadowRoot>"u"?!1:t instanceof ShadowRoot||t instanceof _(t).ShadowRoot}function ot(t){const{overflow:e,overflowX:o,overflowY:n,display:i}=E(t);return/auto|scroll|overlay|hidden|clip/.test(e+n+o)&&!["inline","contents"].includes(i)}function nn(t){return["table","td","th"].includes(J(t))}function ft(t){return[":popover-open",":modal"].some(e=>{try{return t.matches(e)}catch{return!1}})}function kt(t){const e=_t(),o=N(t)?E(t):t;return o.transform!=="none"||o.perspective!=="none"||(o.containerType?o.containerType!=="normal":!1)||!e&&(o.backdropFilter?o.backdropFilter!=="none":!1)||!e&&(o.filter?o.filter!=="none":!1)||["transform","perspective","filter"].some(n=>(o.willChange||"").includes(n))||["paint","layout","strict","content"].some(n=>(o.contain||"").includes(n))}function on(t){let e=z(t);for(;T(e)&&!K(e);){if(kt(e))return e;if(ft(e))return null;e=z(e)}return null}function _t(){return typeof CSS>"u"||!CSS.supports?!1:CSS.supports("-webkit-backdrop-filter","none")}function K(t){return["html","body","#document"].includes(J(t))}function E(t){return _(t).getComputedStyle(t)}function dt(t){return N(t)?{scrollLeft:t.scrollLeft,scrollTop:t.scrollTop}:{scrollLeft:t.scrollX,scrollTop:t.scrollY}}function z(t){if(J(t)==="html")return t;const e=t.assignedSlot||t.parentNode||Vt(t)&&t.host||H(t);return Vt(e)?e.host:e}function Xt(t){const e=z(t);return K(e)?t.ownerDocument?t.ownerDocument.body:t.body:T(e)&&ot(e)?e:Xt(e)}function wt(t,e,o){var n;e===void 0&&(e=[]),o===void 0&&(o=!0);const i=Xt(t),s=i===((n=t.ownerDocument)==null?void 0:n.body),r=_(i);if(s){const l=sn(r);return e.concat(r,r.visualViewport||[],ot(i)?i:[],l&&o?wt(l):[])}return e.concat(i,wt(i,[],o))}function sn(t){return t.parent&&Object.getPrototypeOf(t.parent)?t.frameElement:null}function Yt(t){const e=E(t);let o=parseFloat(e.width)||0,n=parseFloat(e.height)||0;const i=T(t),s=i?t.offsetWidth:o,r=i?t.offsetHeight:n,l=st(o)!==s||st(n)!==r;return l&&(o=s,n=r),{width:o,height:n,$:l}}function Kt(t){return N(t)?t:t.contextElement}function X(t){const e=Kt(t);if(!T(e))return I(1);const o=e.getBoundingClientRect(),{width:n,height:i,$:s}=Yt(e);let r=(s?st(o.width):o.width)/n,l=(s?st(o.height):o.height)/i;return(!r||!Number.isFinite(r))&&(r=1),(!l||!Number.isFinite(l))&&(l=1),{x:r,y:l}}const rn=I(0);function Jt(t){const e=_(t);return!_t()||!e.visualViewport?rn:{x:e.visualViewport.offsetLeft,y:e.visualViewport.offsetTop}}function ln(t,e,o){return e===void 0&&(e=!1),!o||e&&o!==_(t)?!1:e}function nt(t,e,o,n){e===void 0&&(e=!1),o===void 0&&(o=!1);const i=t.getBoundingClientRect(),s=Kt(t);let r=I(1);e&&(n?N(n)&&(r=X(n)):r=X(t));const l=ln(s,o,n)?Jt(s):I(0);let a=(i.left+l.x)/r.x,c=(i.top+l.y)/r.y,d=i.width/r.x,u=i.height/r.y;if(s){const m=_(s),p=n&&N(n)?_(n):n;let h=m,v=h.frameElement;for(;v&&n&&p!==h;){const y=X(v),x=v.getBoundingClientRect(),b=E(v),k=x.left+(v.clientLeft+parseFloat(b.paddingLeft))*y.x,L=x.top+(v.clientTop+parseFloat(b.paddingTop))*y.y;a*=y.x,c*=y.y,d*=y.x,u*=y.y,a+=k,c+=L,h=_(v),v=h.frameElement}}return lt({width:d,height:u,x:a,y:c})}function an(t){let{elements:e,rect:o,offsetParent:n,strategy:i}=t;const s=i==="fixed",r=H(n),l=e?ft(e.floating):!1;if(n===r||l&&s)return o;let a={scrollLeft:0,scrollTop:0},c=I(1);const d=I(0),u=T(n);if((u||!u&&!s)&&((J(n)!=="body"||ot(r))&&(a=dt(n)),T(n))){const m=nt(n);c=X(n),d.x=m.x+n.clientLeft,d.y=m.y+n.clientTop}return{width:o.width*c.x,height:o.height*c.y,x:o.x*c.x-a.scrollLeft*c.x+d.x,y:o.y*c.y-a.scrollTop*c.y+d.y}}function cn(t){return Array.from(t.getClientRects())}function Qt(t){return nt(H(t)).left+dt(t).scrollLeft}function un(t){const e=H(t),o=dt(t),n=t.ownerDocument.body,i=G(e.scrollWidth,e.clientWidth,n.scrollWidth,n.clientWidth),s=G(e.scrollHeight,e.clientHeight,n.scrollHeight,n.clientHeight);let r=-o.scrollLeft+Qt(t);const l=-o.scrollTop;return E(n).direction==="rtl"&&(r+=G(e.clientWidth,n.clientWidth)-i),{width:i,height:s,x:r,y:l}}function fn(t,e){const o=_(t),n=H(t),i=o.visualViewport;let s=n.clientWidth,r=n.clientHeight,l=0,a=0;if(i){s=i.width,r=i.height;const c=_t();(!c||c&&e==="fixed")&&(l=i.offsetLeft,a=i.offsetTop)}return{width:s,height:r,x:l,y:a}}function dn(t,e){const o=nt(t,!0,e==="fixed"),n=o.top+t.clientTop,i=o.left+t.clientLeft,s=T(t)?X(t):I(1),r=t.clientWidth*s.x,l=t.clientHeight*s.y,a=i*s.x,c=n*s.y;return{width:r,height:l,x:a,y:c}}function Bt(t,e,o){let n;if(e==="viewport")n=fn(t,o);else if(e==="document")n=un(H(t));else if(N(e))n=dn(e,o);else{const i=Jt(t);n={...e,x:e.x-i.x,y:e.y-i.y}}return lt(n)}function Ut(t,e){const o=z(t);return o===e||!N(o)||K(o)?!1:E(o).position==="fixed"||Ut(o,e)}function pn(t,e){const o=e.get(t);if(o)return o;let n=wt(t,[],!1).filter(l=>N(l)&&J(l)!=="body"),i=null;const s=E(t).position==="fixed";let r=s?z(t):t;for(;N(r)&&!K(r);){const l=E(r),a=kt(r);!a&&l.position==="fixed"&&(i=null),(s?!a&&!i:!a&&l.position==="static"&&!!i&&["absolute","fixed"].includes(i.position)||ot(r)&&!a&&Ut(t,r))?n=n.filter(d=>d!==r):i=l,r=z(r)}return e.set(t,n),n}function mn(t){let{element:e,boundary:o,rootBoundary:n,strategy:i}=t;const r=[...o==="clippingAncestors"?ft(e)?[]:pn(e,this._c):[].concat(o),n],l=r[0],a=r.reduce((c,d)=>{const u=Bt(e,d,i);return c.top=G(u.top,c.top),c.right=vt(u.right,c.right),c.bottom=vt(u.bottom,c.bottom),c.left=G(u.left,c.left),c},Bt(e,l,i));return{width:a.right-a.left,height:a.bottom-a.top,x:a.left,y:a.top}}function hn(t){const{width:e,height:o}=Yt(t);return{width:e,height:o}}function gn(t,e,o){const n=T(e),i=H(e),s=o==="fixed",r=nt(t,!0,s,e);let l={scrollLeft:0,scrollTop:0};const a=I(0);if(n||!n&&!s)if((J(e)!=="body"||ot(i))&&(l=dt(e)),n){const u=nt(e,!0,s,e);a.x=u.x+e.clientLeft,a.y=u.y+e.clientTop}else i&&(a.x=Qt(i));const c=r.left+l.scrollLeft-a.x,d=r.top+l.scrollTop-a.y;return{x:c,y:d,width:r.width,height:r.height}}function ht(t){return E(t).position==="static"}function Mt(t,e){return!T(t)||E(t).position==="fixed"?null:e?e(t):t.offsetParent}function Zt(t,e){const o=_(t);if(ft(t))return o;if(!T(t)){let i=z(t);for(;i&&!K(i);){if(N(i)&&!ht(i))return i;i=z(i)}return o}let n=Mt(t,e);for(;n&&nn(n)&&ht(n);)n=Mt(n,e);return n&&K(n)&&ht(n)&&!kt(n)?o:n||on(t)||o}const vn=async function(t){const e=this.getOffsetParent||Zt,o=this.getDimensions,n=await o(t.floating);return{reference:gn(t.reference,await e(t.floating),t.strategy),floating:{x:0,y:0,width:n.width,height:n.height}}};function yn(t){return E(t).direction==="rtl"}const wn={convertOffsetParentRelativeRectToViewportRelativeRect:an,getDocumentElement:H,getClippingRect:mn,getOffsetParent:Zt,getElementRects:vn,getClientRects:cn,getDimensions:hn,getScale:X,isElement:N,isRTL:yn},xn=tn,bn=en,An=Ue,Cn=(t,e,o)=>{const n=new Map,i={platform:wn,...o},s={...i.platform,_c:n};return Qe(t,e,{...i,platform:s})},Rn={__name:"HorizontalNavPopper",props:{popperInlineEnd:{type:Boolean,required:!1,default:!1},tag:{type:String,required:!1,default:"div"},contentContainerTag:{type:String,required:!1,default:"div"},isRtl:{type:Boolean,required:!1}},setup(t){const e=t,o=Ct(),n=Z(),i=Z(),s=Z({left:"0px",top:"0px"}),r=async()=>{if(n.value!==void 0&&i.value!==void 0){const{x:u,y:m}=await Cn(n.value,i.value,{placement:e.popperInlineEnd?e.isRtl?"left-start":"right-start":"bottom-start",middleware:[...o.horizontalNavPopoverOffset?[xn(o.horizontalNavPopoverOffset)]:[],An({boundary:document.querySelector("body"),padding:{bottom:16}}),bn({boundary:document.querySelector("body"),padding:{bottom:16}})]});s.value.left=`${u}px`,s.value.top=`${m}px`}};pe(()=>o.horizontalNavType).toMatch(u=>u==="static").then(()=>{me("scroll",r)});const l=Z(!1),a=()=>{l.value=!0,r()},c=()=>{l.value=!1};he(r),gt([()=>o.isAppRTL,()=>o.appContentWidth],r);const d=It();return gt(()=>d.fullPath,c),(u,m)=>(g(),M("div",{class:et(["nav-popper",[{"popper-inline-end":t.popperInlineEnd,"show-content":f(l)}]])},[w("div",{ref_key:"refPopperContainer",ref:n,class:"popper-triggerer",onMouseenter:a,onMouseleave:c},[B(u.$slots,"default")],544),f(P).horizontalNav.transition?typeof f(P).horizontalNav.transition=="string"?(g(),R(ge,{key:1,name:f(P).horizontalNav.transition},{default:A(()=>[Pt(w("div",{ref_key:"refPopper",ref:i,class:"popper-content",style:mt(f(s)),onMouseenter:a,onMouseleave:c},[w("div",null,[B(u.$slots,"content")])],36),[[Ot,f(l)]])]),_:3},8,["name"])):(g(),R(O(f(P).horizontalNav.transition),{key:2},{default:A(()=>[Pt(w("div",{ref_key:"refPopper",ref:i,class:"popper-content",style:mt(f(s)),onMouseenter:a,onMouseleave:c},[w("div",null,[B(u.$slots,"content")])],36),[[Ot,f(l)]])]),_:3})):(g(),M("div",{key:0,ref_key:"refPopper",ref:i,class:"popper-content",style:mt(f(s)),onMouseenter:a,onMouseleave:c},[w("div",null,[B(u.$slots,"content")])],36))],2))}},kn=[{title:"Home",to:{name:"root"},icon:{icon:"tabler-smart-home"}},{title:"Second page",to:{name:"second-page"},icon:{icon:"tabler-file"}}],_n={class:"app-title font-weight-bold leading-normal text-xl text-capitalize"},On={__name:"DefaultLayoutWithHorizontalNav",setup(t){return(e,o)=>{const n=ve("RouterLink");return g(),R(f(Fe),{"nav-items":f(kn)},{navbar:A(()=>{var i;return[S(n,{to:"/",class:"app-logo d-flex align-center gap-x-3"},{default:A(()=>[S(f(ye),{nodes:f(P).app.logo},null,8,["nodes"]),w("h1",_n,tt(f(P).app.title),1)]),_:1}),S(we),f(P).app.i18n.enable&&((i=f(P).app.i18n.langConfig)!=null&&i.length)?(g(),R(Ee,{key:0,languages:f(P).app.i18n.langConfig},null,8,["languages"])):Rt("",!0),S(be,{class:"me-2"}),S(Ae)]}),footer:A(()=>[S(xe)]),default:A(()=>[B(e.$slots,"default")]),_:3},8,["nav-items"])}}};export{On as default};
