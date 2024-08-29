const fs = require('fs')
const path = require('path')
const iconv = require('iconv-lite');
const zlib = require('node:zlib');
const { promisify } = require('node:util');
const { pipeline } = require('node:stream');

const pipe = promisify(pipeline);

const unzip = async (pathname) => {
  const ext = path.extname(pathname);
  const filename = path.basename(pathname, ext);
  const dir = path.dirname(pathname);
  const outputname = `${dir}/${filename}.u${ext}`;

  const input = fs.createReadStream(pathname);
  const output = fs.createWriteStream(outputname);

  console.log('outputname:', outputname);

  return pipe(input, zlib.createInflate(), output);
};

const recurse = async (dir, cb) => {
  const f = fs.readdirSync(dir, { withFileTypes: true });

  for (const entry of f) {
    console.log('in', entry.name);
    if (entry.name.startsWith('.')) {
      continue;
    }

    const fullname = path.join(dir, entry.name);

    if (entry.isDirectory()) {
      await recurse(fullname, cb);
    } else {
      await cb(fullname);
      console.log('  done', entry.name);
    }
  }
};

// (async () => {
//   const dir = '/Users/ankh/Documents/tvs';
//
//   try {
//     await recurse(path.normalize(dir), async (file) => {
//       if (file.endsWith('.d.html')) {
//         fs.renameSync(file, file.replace('.d.html', '.html'))
//       } else {
//         fs.unlinkSync(file);
//       }
//
//       // console.log(file);
//       //
//       // const input = fs.createReadStream(file);
//       // const output = fs.createWriteStream(file.replace('.html', '.d.html'));
//       // await pipe(input, iconv.decodeStream('win1251'), output);
//       //
//       // console.log('done?', file);
//
// // if (file.endsWith('.u.html')) {
//       //   return;
//       // }
//
//       // try {
//       //   await unzip(file);
//       //   fs.unlinkSync(file);
//       // } catch (e) {
//       //   console.log(e);
//       // }
//     });
//   } catch (e) {
//     console.log(e);
//   }
// })();

const AWS = require('aws-sdk')
const s3Zip = require('s3-zip')
const XmlStream = require('xml-stream')

const region = 'eu-west-1'
const bucket = 'cloud-cube-eu'
const folder = 'x1roah3goj1m/'
const s3 = new AWS.S3({
  region: region,
  accessKeyId: '',
  secretAccessKey: '',
})
const params = {
  Bucket: bucket,
  Prefix: folder,
  MaxKeys: 1000,
}

const filesArray = []
const list = s3.listObjects(params);
const skip = 'public/annet-li/';
const slice = folder.length;

list.eachPage((err, data, done) => {
  if (err) {
    console.log(err);
  } else {
    if (data) {
      const list = data.Contents.map(({ Key }) => Key.slice(slice)).filter((key) => !key.startsWith(skip));

      filesArray.push(...list);
    } else {
      zip(filesArray);
    }
  }

  done();
});
// const files = list.createReadStream()
// const xml = new XmlStream(files)
// xml.collect('Key')
// xml.on('endElement: Key', function(item) {
//   filesArray.push(item['$text'].substr(folder.length))
// })
//
// xml
//   .on('end', function () {
//     zip(filesArray)
//   })

function zip(files) {
  console.log('Files:', files.length)
  const output = fs.createWriteStream(path.join(__dirname, 'use-s3-zip.zip'))
  s3Zip
    .archive({ debug: true, s3, region: region, bucket: bucket, preserveFolderStructure: true }, folder, files)
    .pipe(output)
}
