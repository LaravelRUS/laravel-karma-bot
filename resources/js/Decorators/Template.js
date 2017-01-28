export default function Template(body) {
    return ctx => { ctx.$template = body; };
}