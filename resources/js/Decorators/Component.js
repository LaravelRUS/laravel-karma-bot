export default function Component(name) {
    return ctx => { ctx['$name'] = name; };
}