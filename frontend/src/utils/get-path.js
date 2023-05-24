export function getPath(segments) {
  return segments
    .map((segment) =>
      segment.toString().replace(/\/+$/, '').replace(/^\/+/, ''),
    )
    .join('/')
    .replace(/^/, '/');
}
